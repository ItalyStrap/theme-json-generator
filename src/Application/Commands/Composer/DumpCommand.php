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
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\FilesFinder;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * @psalm-api
 */
final class DumpCommand extends BaseCommand
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

    private Dump $dump;

    private ConfigInterface $config;

    private FilesFinder $filesFinder;

    private EventDispatcherInterface $dispatcher;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        Dump $dump,
        ConfigInterface $config,
        FilesFinder $filesFinder
    ) {
        $this->dispatcher = $dispatcher;
        $this->dump = $dump;
        $this->config = $config;
        $this->filesFinder = $filesFinder;
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
        $composer = $this->requireComposer();
        $rootFolder = $this->rootFolder();

        $output->writeln('<info>Generating theme.json file</info>');

        $package = $composer->getPackage();
        $this->config->merge($package->getExtra()[self::COMPOSER_EXTRA_THEME_JSON_KEY] ?? []);

        /**
         * @var callable $callback
         */
        $callback = $this->config->get(self::CALLBACK);

        $this->dispatcher->addListener(
            GeneratingFile::class,
            static function (GeneratingFile $event) use ($output): void {
                $output->writeln(\sprintf(
                    '<info>Generating %s file</info>',
                    $event->getFileName()
                ));
            }
        );

        $this->dispatcher->addListener(
            GeneratedFile ::class,
            static function (GeneratedFile $event) use ($output): void {
                $output->writeln(\sprintf(
                    '<info>Generated %s file</info>',
                    $event->getFileName()
                ));
                $output->writeln('========================');
            }
        );

        $message = new DumpMessage(
            $rootFolder,
            $this->config->get(self::PATH_FOR_THEME_SASS, ''),
            $input->getOption('dry-run')
        );

        try {
            $this->dump->processBlueprint($message, 'theme', $callback);
            $output->writeln('<info>Generated theme.json file</info>');
        } catch (\Exception $exception) {
            $output->writeln($exception->getMessage());
        }

        $this->dump->handle($message);

        if ($input->getOption(ValidateCommand::NAME)) {
            $process = new Process(['php', 'vendor/bin/theme-json', ValidateCommand::NAME]);
            $process->run();

            $output->write($process->getOutput());

            return $process->getExitCode();
        }

        return Command::SUCCESS;
    }
}
