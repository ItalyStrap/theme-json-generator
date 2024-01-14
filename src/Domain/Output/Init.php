<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Output;

use Brick\VarExporter\VarExporter;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\InitMessage;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\DataFromJsonTrait;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\EntryPointCanNotBeCreated;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\EntryPointCreated;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\EntryPointDoesNotExist;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\FilesFinder;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\NodeFinder;
use PhpParser\ParserFactory;
use Psr\EventDispatcher\EventDispatcherInterface;
use Webimpress\SafeWriter\Exception\ExceptionInterface as FileWriterException;
use Webimpress\SafeWriter\FileWriter;

/**
 * @psalm-api
 */
class Init
{
    use DataFromJsonTrait;

    /**
     * @var string
     */
    public const ENTRY_POINT_TEMPLATE = <<<'TEMPLATE'
<?php

declare(strict_types=1);

use ItalyStrap\ThemeJsonGenerator\Application\Config\Blueprint;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\SectionNames;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\CollectionInterface;
use Psr\Container\ContainerInterface;

return static function (ContainerInterface $container, CollectionInterface $collection, Blueprint $blueprint) {

	$blueprint->merge(%s);
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

    public function handle(InitMessage $command): void
    {
        foreach ($this->filesFinder->find($command->getRootFolder(), 'json') as $file) {
            $this->generateEntryPointDataFile($file);
        }
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
                $this->dispatcher->dispatch(new EntryPointCanNotBeCreated($entryPointRealPath));
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

        $code = \file_get_contents(__DIR__ . '/../../../src/Domain/Input/SectionNames.php');
        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        try {
            $ast = $parser->parse($code);
        } catch (Error $error) {
            echo sprintf('Parse error: %s%s', $error->getMessage(), PHP_EOL);
            return '';
        }

        $nodeFinder = new NodeFinder();
        $constants = $nodeFinder->findInstanceOf($ast, ClassConst::class);

        $search = [];
        $replace = [];

        foreach ($constants as $constant) {
            foreach ($constant->consts as $const) {
                $name = $const->name->toString();
                $value = null;

                if ($const->value instanceof String_) {
                    $value = $const->value->value;
                }

                if ($value !== null) {
                    $search[] = sprintf("'%s'", $value);
                    $replace[] = 'SectionNames::' . $name;
                }
            }
        }

        return \str_replace($search, $replace, $dataExported);
    }
}
