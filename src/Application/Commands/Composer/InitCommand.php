<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer;

use Brick\VarExporter\VarExporter;
use Composer\Command\BaseCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\DataFromJsonTrait;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\RootFolderTrait;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\EntryPointCanNotBeCreated;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\EntryPointCreated;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\EntryPointDoesNotExist;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Init;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\FilesFinder;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webimpress\SafeWriter\Exception\ExceptionInterface as FileWriterException;
use Webimpress\SafeWriter\FileWriter;

class InitCommand extends BaseCommand
{
    use RootFolderTrait;
    use DataFromJsonTrait;

    public const NAME = 'init';

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

    private EventDispatcherInterface $dispatcher;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        Init $init,
        FilesFinder $filesFinder
    ) {
        $this->dispatcher = $dispatcher;
        $this->init = $init;
        $this->filesFinder = $filesFinder;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription('Initialize theme.json file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rootFolder = $this->rootFolder();

        $this->dispatcher->addListener(
            EntryPointDoesNotExist::class,
            static function (EntryPointDoesNotExist $event) use ($output): void {
                $output->writeln(\sprintf(
                    'Entry file does not exist, creating %s file',
                    $event->getFile()
                ));
            }
        );

        $this->dispatcher->addListener(
            EntryPointCreated::class,
            static function (EntryPointCreated $event) use ($output): void {
                $output->writeln(\sprintf(
                    'Entry file %s created',
                    $event->getFile()
                ));
            }
        );

        $this->dispatcher->addListener(
            EntryPointCanNotBeCreated::class,
            static function (EntryPointCanNotBeCreated $event) use ($output): void {
                $output->writeln(\sprintf(
                    'Entry file %s cannot be created because of an error',
                    $event->getFile()
                ));
            }
        );

        $command = new class ($rootFolder) {
            private string $rootFolder = '';

            public function __construct(string $rootFolder)
            {
                $this->rootFolder = $rootFolder;
            }

            public function getRootFolder(): string
            {
                return $this->rootFolder;
            }
        };

        $this->init->handle($command);

        return Command::SUCCESS;
    }
}
