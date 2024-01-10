<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer;

use Composer\Command\BaseCommand;
use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\RootFolderTrait;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Dump;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\FilesFinder;
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

    private ConfigInterface $config;
    private Dump $dump;
    private FilesFinder $filesFinder;

    public function __construct(
        Dump $dump,
        ConfigInterface $config,
        FilesFinder $filesFinder
    ) {
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
         *       --delete -D (delete the json file before generate it) before deleting the file, check if the related php file exists
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

        try {
            $this->dump->processBlueprint($callback, $rootFolder, 'theme');
            $output->writeln('<info>Generated theme.json file</info>');
        } catch (\Exception $exception) {
            $output->writeln($exception->getMessage());
        }

        $command = new class (
            $rootFolder,
            $input->getOption('dry-run')
        ) {
            private string $rootFolder = '';
            private bool $dry_run;

            public function __construct(
                string $rootFolder,
                bool $dry_run
            ) {
                $this->rootFolder = $rootFolder;
                $this->dry_run = $dry_run;
            }

            public function getRootFolder(): string
            {
                return $this->rootFolder;
            }

            public function isDryRun(): bool
            {
                return $this->dry_run;
            }
        };

        $this->dump->handle($command);

        if ($input->getOption(ValidateCommand::NAME)) {
            $process = new Process(['php', 'vendor/bin/theme-json', ValidateCommand::NAME]);
            $process->run();

            $output->write($process->getOutput());

            return $process->getExitCode();
        }

        return Command::SUCCESS;
    }
}
