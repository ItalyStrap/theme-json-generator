<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer;

use Composer\Command\BaseCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\DataFromJsonTrait;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\FilesFinderTrait;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\RootFolderTrait;
use JsonSchema\Validator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ValidateCommand extends BaseCommand
{
    use RootFolderTrait;
    use FilesFinderTrait;
    use DataFromJsonTrait;

    protected function configure()
    {
        $this->setName('validate');
        $this->setDescription('Validate theme.json file');
    }

    /**
     * @todo add a rule to exclude the theme.schema.json file to .gitignore
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rootFolder = $this->rootFolder();
        $schemaPath = $rootFolder . '/theme.schema.json';

        try {
            if (!\file_exists($schemaPath) || $this->isFileSchemaOlderThanOneWeek($schemaPath)) {
                $this->createFileSchema($schemaPath);
            }
        } catch (\Exception $e) {
            $output->writeln('<error>Errore: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }

        $jsonFiles = $this->findJsonFiles($rootFolder);

        foreach ($jsonFiles as $file) {
            $this->validateJsonFile($file, $output, $schemaPath);
        }

        return Command::SUCCESS;
    }

    private function validateJsonFile(
        string $file,
        OutputInterface $output,
        string $schemaPath
    ): void {
        $output->writeln([
            '========================',
            \sprintf(
                'Validating <info>%s</info>',
                \basename($file)
            ),
        ]);

        $validator = new Validator();
        $data = $this->objectFromPath($file);
        $validator->validate($data, (object)['$ref' => 'file://' . \realpath($schemaPath)]);

        if ($validator->isValid()) {
            $output->writeln([
                \sprintf(
                    '<info>%s</info> is valid',
                    \basename($file)
                ),
                '========================',
            ]);

            return;
        }

        $output->writeln(PHP_EOL);

        foreach ($validator->getErrors() as $error) {
            $output->writeln(\sprintf(
                '<error>[%s]</error> is not valid. %s',
                $error['property'],
                $error['message']
            ));
        }
    }

    private function isFileSchemaOlderThanOneWeek(string $schemaPath): bool
    {
        $lastModified = \filemtime($schemaPath);
        $oneWeekAgo = \time() - 7 * 24 * 60 * 60;
        return $lastModified < $oneWeekAgo;
    }

    private function createFileSchema(string $schemaPath): void
    {
        $schemaContent = \file_get_contents(
            'https://raw.githubusercontent.com/WordPress/gutenberg/trunk/schemas/json/theme.json'
        );

        if ($schemaContent === false) {
            throw new \RuntimeException("Impossible to download the schema");
        }

        if (!\file_put_contents($schemaPath, $schemaContent)) {
            throw new \RuntimeException("Impossible to write the schema");
        }
    }
}
