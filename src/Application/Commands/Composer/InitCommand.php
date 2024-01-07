<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer;

use Brick\VarExporter\VarExporter;
use Composer\Command\BaseCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\DataFromJsonTrait;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\RootFolderTrait;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Init;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\FilesFinder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webimpress\SafeWriter\Exception\ExceptionInterface as FileWriterException;
use Webimpress\SafeWriter\FileWriter;

class InitCommand extends BaseCommand
{
    use RootFolderTrait;
    use DataFromJsonTrait;

    /**
     * @var string
     */
    public const ENTRY_POINT_TEMPLATE = <<<'TEMPLATE'
<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;

return static function (ContainerInterface $container) {

	%s
};

TEMPLATE;

    private FilesFinder $filesFinder;
    private Init $init;

    public function __construct(
        Init $init,
        FilesFinder $filesFinder
    ) {
        $this->init = $init;
        $this->filesFinder = $filesFinder;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('init');
        $this->setDescription('Initialize theme.json file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rootFolder = $this->rootFolder();

        foreach ($this->filesFinder->find($rootFolder, 'json') as $file) {
            $output->writeln('========================');
            $this->generateEntryPointDataFile($file . '.dist.php', $output, (string)$file);
        }

        return Command::SUCCESS;
    }

    private function generateEntryPointDataFile(
        string $themePhpPath,
        OutputInterface $output,
        string $themeJsonPath
    ): void {
        if (!\file_exists($themePhpPath)) {
            $phpFileName = \basename($themePhpPath);
            $output->writeln(\sprintf(
                'Entry file does not exist, creating %s file',
                $phpFileName
            ));
            $dataExported = $this->exportFromThemeJsonIfExists($themeJsonPath);

            $content = \sprintf(
                self::ENTRY_POINT_TEMPLATE,
                \trim($dataExported)
            );

            try {
                FileWriter::writeFile($themePhpPath, $content, 0666);
            } catch (FileWriterException $fileWriterException) {
                $output->writeln(\sprintf(
                    'Entry file %s cannot be created because of an error',
                    $phpFileName
                ));
                return;
            }

            $output->writeln(\sprintf(
                'Entry file %s created',
                $phpFileName
            ));
        }
    }

    private function exportFromThemeJsonIfExists(string $themeJsonPath)
    {
        if (!\file_exists($themeJsonPath)) {
            return 'return [];';
        }

        $data = $this->associativeFromPath($themeJsonPath);

        $dataExported = VarExporter::export($data, VarExporter::ADD_RETURN | VarExporter::TRAILING_COMMA_IN_ARRAY, 1);
        return \str_replace(
            [
                "'\$schema'",
                "'version'",
            ],
            [
                'SectionNames::SCHEMA',
                'SectionNames::VERSION',
            ],
            $dataExported
        );
    }
}
