<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Middlewares;

use Brick\VarExporter\VarExporter;
use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\Pipeline\MiddlewareInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\EntryPointCanNotBeCreated;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\EntryPointCreated;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\EntryPointDoesNotExist;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\DataFromJsonTrait;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\FilesFinder;
use PhpParser\Error;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\NodeFinder;
use PhpParser\ParserFactory;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Command\Command;
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

    private EventDispatcherInterface $dispatcher;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        FilesFinder $filesFinder
    ) {
        $this->filesFinder = $filesFinder;
        $this->dispatcher = $dispatcher;
    }

    public function process(object $message, HandlerInterface $handler): int
    {
        // TODO: This should be refactored
        $this->needsRefactoringAddSubscriber($this->dispatcher);

        foreach ($this->filesFinder->find($message->getRootFolder(), 'json') as $file) {
            $this->generateEntryPointDataFile($file);
        }

        return Command::SUCCESS;
    }

    private function generateEntryPointDataFile(
        \SplFileInfo $file
    ): void {
        $entryPointFileName = $file->getFilename() . self::ENTRY_POINT_EXTENSION;
        $entryPointRealPath = $file->getPath() . DIRECTORY_SEPARATOR . $entryPointFileName;
        if (!\file_exists($entryPointRealPath)) {
            $this->dispatcher->dispatch(new EntryPointDoesNotExist($entryPointRealPath));

            $dataExported = $this->exportFromThemeJsonIfExists($file);
            $content = \sprintf(
                self::ENTRY_POINT_TEMPLATE,
                \trim($dataExported)
            );

            try {
                FileWriter::writeFile($entryPointRealPath, $content, 0666);
            } catch (FileWriterException $fileWriterException) {
                $this->dispatcher->dispatch(new EntryPointCanNotBeCreated(
                    $entryPointRealPath,
                    $fileWriterException
                ));
                return;
            }

            $this->dispatcher->dispatch(new EntryPointCreated($entryPointRealPath));
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

    private function needsRefactoringAddSubscriber($subscriber): void
    {
        /**
         * OutputInterface $output
         */
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();

        $subscriber->addListener(
            EntryPointDoesNotExist::class,
            static function (EntryPointDoesNotExist $event) use ($output): void {
                $output->writeln(\sprintf(
                    'Entry file does not exist, creating %s file',
                    $event->getFile()
                ));
            }
        );

        $subscriber->addListener(
            EntryPointCreated::class,
            static function (EntryPointCreated $event) use ($output): void {
                $output->writeln(\sprintf(
                    'Entry file %s created',
                    $event->getFile()
                ));
            }
        );

        $subscriber->addListener(
            EntryPointCanNotBeCreated::class,
            static function (EntryPointCanNotBeCreated $event) use ($output): void {
                $output->writeln(\sprintf(
                    'Entry file %s cannot be created because of %s',
                    $event->getFile(),
                    $event->getException()->getMessage()
                ));
            }
        );
    }
}
