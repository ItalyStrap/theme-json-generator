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
            'validate',
            null,
            InputOption::VALUE_NONE,
            \sprintf(
                'If set, %s will validate the generated theme.json file.',
                self::NAME
            )
        );

        /**
         * @todo other options:
         *       --no-pretty-print
         *       --indent=2 (default is 4)
         *       --config (provide a custom config file)
         *       --delete -D (delete the generated theme.json file)
         */
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getIO();
        $composer = $this->requireComposer();
        $rootFolder = $this->rootFolder();

        // Get the value of --dry-run
        $dry_run = $input->getOption('dry-run');
        // Get the value of --validate
        $validate = $input->getOption('validate');

        $output->writeln('<info>Generating theme.json file</info>');

        $package = $composer->getPackage();
        $this->config->merge($package->getExtra()[self::COMPOSER_EXTRA_THEME_JSON_KEY] ?? []);

        /**
         * @var callable $callback
         */
        $callback = $this->config->get(self::CALLBACK);

        try {
            $this->dump->processBlueprint($callback, $rootFolder, 'theme');
        } catch (\Exception $exception) {
            $output->writeln($exception->getMessage());
        }

        /**
         * Let's test the new workflow
         * @todo add key for json file name to be created
         * @example $name => $file
         *         'theme' => 'theme.dist.json'
         */
        foreach ($this->filesFinder->find($rootFolder, 'php') as $fileName => $file) {
            $folderToCreateFile = $rootFolder;
            if (\strpos((string)$file, '/styles') !== false) {
                $folderToCreateFile .= '/styles';
            }
            $this->dump->executeCallable(require $file, $folderToCreateFile, $fileName);
        }

        $output->writeln('<info>Generated theme.json file</info>');

        return Command::SUCCESS;
    }
}
