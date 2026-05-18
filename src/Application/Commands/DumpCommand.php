<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands;

use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\RootFolderTrait;
use ItalyStrap\ThemeJsonGenerator\Application\DumpMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

#[AsCommand(name: DumpCommand::NAME, description: DumpCommand::DESCRIPTION)]
final class DumpCommand extends Command
{
    use RootFolderTrait;

    /**
     * @var string
     */
    public const NAME = 'dump';

    public const DESCRIPTION = 'Generate theme.json file';

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

    private HandlerInterface $handler;

    public function __construct(
        HandlerInterface $handler
    ) {
        $this->handler = $handler;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription(self::DESCRIPTION);
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
        $rootFolder = $this->rootFolder((string)$input->getOption('path'));

        $message = new DumpMessage(
            $rootFolder,
            '',
            (bool)$input->getOption('dry-run'),
            (string)$input->getOption(self::FILE)
        );

        try {
            return (int)$this->handler->handle($message);
        } catch (\Exception $exception) {
            $output->writeln('<error>Error: ' . $exception->getMessage() . '</error>');
            return Command::FAILURE;
        }

//        if ($input->getOption(ValidateCommand::NAME)) {
//            $process = new Process(['php', 'vendor/bin/theme-json', ValidateCommand::NAME]);
//            $process->run();
//
//            $output->write($process->getOutput());
//
//            return (int)$process->getExitCode();
//        }

//        return Command::SUCCESS;
    }
}
