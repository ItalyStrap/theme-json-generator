<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Middlewares;

use Brick\VarExporter\VarExporter;
use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\Pipeline\MiddlewareInterface;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\DataFromJsonTrait;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\FilesFinder;
use PhpParser\Error;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\NodeFinder;
use PhpParser\ParserFactory;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Webimpress\SafeWriter\Exception\ExceptionInterface as FileWriterException;
use Webimpress\SafeWriter\FileWriter;
use Webmozart\Assert\Assert;

/**
 * @psalm-api
 */
class Init implements MiddlewareInterface
{
    use DataFromJsonTrait;

    /**
     * @var string
     */
    public const ENTRY_POINT_TEMPLATE = <<<'TEMPLATE'
<?php

declare(strict_types=1);

use ItalyStrap\ThemeJsonGenerator\Application\Config\ThemeJson;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\SectionNames;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetsInterface;
use Psr\Container\ContainerInterface;

return static function (ContainerInterface $container, PresetsInterface $presets, ThemeJson $themeJson) {

	$themeJson->merge(%s);
};

TEMPLATE;

    public const ENTRY_POINT_EXTENSION = '.php';

    private FilesFinder $filesFinder;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        FilesFinder $filesFinder
    ) {
        $this->filesFinder = $filesFinder;
    }

    public function process(object $message, HandlerInterface $handler): int
    {
        /**
         * OutputInterface $output
         */
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();

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

        $dataExported = VarExporter::export(
            $data,
            VarExporter::TRAILING_COMMA_IN_ARRAY,
            1
        );

        $search = [];
        $replace = [];

        $code = (string)\file_get_contents(__DIR__ . '/../../../src/Domain/Input/SectionNames.php');
        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);

        try {
            $ast = (array)$parser->parse($code);

            $nodeFinder = new NodeFinder();
            $constants = $nodeFinder->findInstanceOf($ast, ClassConst::class);

            /**
             * @var ClassConst $constant
             */
            foreach ($constants as $constant) {
                Assert::propertyExists($constant, 'consts');
                foreach ($constant->consts as $const) {
                    $name = $const->name->toString();
                    $value = null;

                    if ($const->value instanceof String_) {
                        $value = $const->value->value;
                    }

                    if ($value !== null) {
                        $search[] = \sprintf("'%s'", $value);
                        $replace[] = 'SectionNames::' . $name;
                    }
                }
            }
        } catch (Error $error) {
            echo sprintf('Parse error: %s%s', $error->getMessage(), PHP_EOL);
            return '';
        }

        return \str_replace($search, $replace, $dataExported);
    }
}
