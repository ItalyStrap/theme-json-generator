<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Application\Middlewares;

use Brick\VarExporter\VarExporter;
use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\Pipeline\MiddlewareInterface;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Message;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Filesystem\DataFromJsonTrait;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Filesystem\FilesFinder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Webimpress\SafeWriter\Exception\ExceptionInterface as FileWriterException;
use Webimpress\SafeWriter\FileWriter;

final class GenerateThemeJsonConfigFileFromThemeJson implements MiddlewareInterface
{
    use DataFromJsonTrait;

    /**
     * @var string
     */
    public const ENTRY_POINT_TEMPLATE = <<<'TEMPLATE'
<?php

declare(strict_types=1);

use ItalyStrap\ThemeJsonGenerator\Api\ThemeJson;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetsInterface;
use Psr\Container\ContainerInterface;

return static function (
    ContainerInterface $container,
    PresetsInterface $presets,
    ThemeJson $themeJson
    ): void {

	$themeJson->merge(%s);
};

TEMPLATE;

    public const ENTRY_POINT_EXTENSION = '.php';

    public function __construct(private FilesFinder $filesFinder)
    {
    }

    /**
     * @phpstan-param Message $message
     */
    public function process(object $message, HandlerInterface $handler): int
    {
        /**
         * OutputInterface $output
         */
        $output = new ConsoleOutput();

        foreach ($this->filesFinder->find($message->getRootFolder(), 'json') as $file) {
            $this->generateEntryPointDataFile($output, $file);
        }

        return Command::SUCCESS;
    }

    private function generateEntryPointDataFile(
        OutputInterface $output,
        \SplFileInfo $file
    ): void {
        $entryPointFileName = $file->getFilename() . self::ENTRY_POINT_EXTENSION;
        $entryPointRealPath = $file->getPath() . DIRECTORY_SEPARATOR . $entryPointFileName;
        if (!\file_exists($entryPointRealPath)) {
            $output->writeln(\sprintf(
                'Entry file does not exist, creating %s file',
                $entryPointRealPath
            ));

            $dataExported = $this->exportFromThemeJsonIfExists($file);
            $content = \sprintf(
                self::ENTRY_POINT_TEMPLATE,
                \trim($dataExported)
            );

            try {
                FileWriter::writeFile($entryPointRealPath, $content, 0666);
            } catch (FileWriterException $fileWriterException) {
                $output->writeln(\sprintf(
                    'Entry file %s cannot be created because of %s',
                    $entryPointRealPath,
                    $fileWriterException->getMessage()
                ));
                return;
            }

            $output->writeln(\sprintf(
                'Entry file %s created',
                $entryPointRealPath
            ));
        }
    }

    private function exportFromThemeJsonIfExists(\SplFileInfo $file): string
    {
        $data = $this->associativeFromPath((string)$file);

        return VarExporter::export(
            $data,
            VarExporter::TRAILING_COMMA_IN_ARRAY,
            1
        );
    }
}
