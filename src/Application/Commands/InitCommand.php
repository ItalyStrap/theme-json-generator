<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands;

use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\RootFolderTrait;
use ItalyStrap\ThemeJsonGenerator\Application\Message;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\DataFromJsonTrait;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Handler\ConsoleHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: InitCommand::NAME, description: InitCommand::DESCRIPTION)]
class InitCommand extends Command
{
    use RootFolderTrait;
    use DataFromJsonTrait;

    public const NAME = 'init';

    public const DESCRIPTION = 'Initialize theme.json file';

    public function __construct(
        private readonly ConsoleHandler $handler,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription(self::DESCRIPTION);

        $this->addOption(
            'styles',
            's',
            \Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL,
            'Init JSON file inside styles folder'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rootFolder = $this->rootFolder();

        $message = new Message($rootFolder);

        try {
            return $this->handler->handle($message);
        } catch (\Exception $exception) {
            $output->writeln('<error>Error: ' . $exception->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
