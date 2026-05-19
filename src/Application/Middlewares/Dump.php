<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Middlewares;

use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\Pipeline\MiddlewareInterface;
use ItalyStrap\ThemeJsonGenerator\Api\ThemeJson;
use ItalyStrap\ThemeJsonGenerator\Application\DumpMessage;
use ItalyStrap\ThemeJsonGenerator\Application\ThemeJsonContainerFactoryInterface;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\FilesFinder;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\JsonFileWriter;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\ScssFileWriter;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Handler\ConsoleHandler;
use Symfony\Component\Console\Output\OutputInterface;

final readonly class Dump implements MiddlewareInterface
{
    /**
     * @var string
     */
    public const M_NO_FILE_FOUND = 'No file found';

    public const JSON_FILE_SUFFIX = '.json';

    public function __construct(
        private FilesFinder $filesFinder,
        private ThemeJsonContainerFactoryInterface $containerFactory
    ) {
    }

    /**
     * @phpstan-param DumpMessage $message
     * @phpstan-param ConsoleHandler $handler
     */
    public function process(object $message, HandlerInterface $handler): int
    {
        /**
         * OutputInterface $output
         */
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();

        $count = 0;

        /**
         * Let's test the new workflow
         * @example $name => $file
         *         'theme' => 'theme.json'
         */
        foreach ($this->filesFinder->find($message->getRootFolder(), 'php') as $fileName => $file) {
            $themeJson = $this->containerFactory->execute(require $file);
            $count++;

            if ($message->isDryRun()) {
                $output->writeln(\sprintf(
                    '<comment>Dry run mode enabled, skipping file generation for %s</comment>',
                    $fileName
                ));
                continue;
            }

            $this->generateJsonFile($output, $message, $fileName, $file, $themeJson);
            $this->generateScssFile($output, $message, $fileName, $themeJson);
        }

        if ($count === 0) {
            $output->writeln(self::M_NO_FILE_FOUND);
        }

        return $handler->handle($message);
    }

    /**
     * @param ThemeJson<array-key, mixed> $themeJson
     */
    private function generateJsonFile(
        OutputInterface $output,
        DumpMessage $message,
        string $fileName,
        \SplFileInfo $file,
        ThemeJson $themeJson
    ): void {

        $output->writeln(\sprintf(
            '<info>Generating %s file</info>',
            $fileName . self::JSON_FILE_SUFFIX
        ));

        (new JsonFileWriter($this->filesFinder->resolveJsonFile($file)))
            ->write($themeJson);

        $output->writeln(\sprintf(
            '<info>Generated %s file</info>',
            $fileName . self::JSON_FILE_SUFFIX
        ));
        $output->writeln('========================');
    }

    /**
     * @param ThemeJson<array-key, mixed> $themeJson
     */
    private function generateScssFile(
        OutputInterface $output,
        DumpMessage $message,
        string $fileName,
        ThemeJson $themeJson
    ): void {
        $path_for_theme_sass = $message->getRootFolder() . DIRECTORY_SEPARATOR . $message->getSassFolder();
        if ($message->getSassFolder() !== '' && \is_writable($path_for_theme_sass)) {
            $output->writeln(\sprintf(
                '<info>Generating %s file</info>',
                $fileName . '.scss'
            ));

            (new ScssFileWriter($path_for_theme_sass . DIRECTORY_SEPARATOR . $fileName . '.scss'))
                ->write($themeJson);

            $output->writeln(\sprintf(
                '<info>Generated %s file</info>',
                $fileName . '.scss'
            ));
            $output->writeln('========================');
        }
    }
}
