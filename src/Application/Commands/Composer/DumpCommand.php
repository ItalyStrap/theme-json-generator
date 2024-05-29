<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer;

use Composer\Command\BaseCommand;
use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\DumpMessage;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\RootFolderTrait;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Dump;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\GeneratedFile;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\GeneratingFile;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\NoFileFound;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * @psalm-api
 */
final class DumpCommand extends Command
{
    use RootFolderTrait;

    /**
     * @var string
     */
    public const NAME = 'dump';

    /**
     * @var string
     */
    public const COMPOSER_EXTRA_THEME_JSON_KEY = 'theme-json';

    /**
     * @var string
     */
    public const CALLBACK = 'callable';

    /**
     * @var string
     */
    public const PATH_FOR_THEME_SASS = 'path-for-theme-sass';

    /**
     * @var string
     */
    public const FILE = 'file';

    private Dump $dump;

    private ConfigInterface $config;

    private \Symfony\Component\EventDispatcher\EventDispatcher $subscriber;

    public function __construct(
        \Symfony\Component\EventDispatcher\EventDispatcher $subscriber,
        Dump $dump,
        ConfigInterface $config
    ) {
        $this->subscriber = $subscriber;
        $this->dump = $dump;
        $this->config = $config;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription('Generate theme.json file');
        $this->setHelp('This command generate theme.json file');

        $this->addOption(
            'dry-run',
            null,
            InputOption::VALUE_NONE,
            \sprintf(
                'If set, %s will run in dry run mode.',
                self::NAME
            )
        );

        $this->addOption(
            ValidateCommand::NAME,
            null,
            InputOption::VALUE_NONE,
            \sprintf(
                'If set, %s will validate all the generated json files.',
                self::NAME
            )
        );

        $this->addOption(
            'path',
            'p',
            InputOption::VALUE_OPTIONAL,
            \sprintf(
                'If set, %s will generate the json file in the specified path.',
                self::NAME
            )
        );

        $this->addOption(
            self::FILE,
            null,
            InputOption::VALUE_OPTIONAL,
            \sprintf(
                'If set, %s will generate only the specified file.',
                self::NAME
            )
        );

        /**
         * @todo other options:
         *       --no-pretty-print
         *       --indent=2 (default is 4)
         *       --config (provide a custom config file)
         *       --delete -D (delete the json file before generate it) before deleting the file
         *                   check if the related php file exists
         */
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->subscriber->addListener(
            GeneratingFile::class,
            static function (GeneratingFile $event) use ($output): void {
                $output->writeln(\sprintf(
                    '<info>Generating %s file</info>',
                    $event->getFileName()
                ));
            }
        );

        $this->subscriber->addListener(
            GeneratedFile ::class,
            static function (GeneratedFile $event) use ($output): void {
                $output->writeln(\sprintf(
                    '<info>Generated %s file</info>',
                    $event->getFileName()
                ));
                $output->writeln('========================');
            }
        );

        $this->subscriber->addListener(
            NoFileFound::class,
            static function (NoFileFound $event) use ($output): void {
                $output->writeln(NoFileFound::M_NO_FILE_FOUND);
            }
        );

        $rootFolder = $this->rootFolder((string)$input->getOption('path'));

        $message = new DumpMessage(
            $rootFolder,
            '',
            (bool)$input->getOption('dry-run'),
            (string)$input->getOption(self::FILE)
        );

        $this->dump->handle($message);

        if ($input->getOption(ValidateCommand::NAME)) {
            $process = new Process(['php', 'vendor/bin/theme-json', ValidateCommand::NAME]);
            $process->run();

            $output->write($process->getOutput());

            return (int)$process->getExitCode();
        }

        return Command::SUCCESS;
    }
}
