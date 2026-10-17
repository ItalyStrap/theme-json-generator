<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Application\Commands;

use ItalyStrap\ThemeJsonGenerator\Cli\Application\Commands\Utils\RootFolderTrait;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Message;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Filesystem\DataFromJsonTrait;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Handler\ConsoleHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: InitCommand::NAME, description: InitCommand::DESCRIPTION)]
final class InitCommand extends Command
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
            InputOption::VALUE_OPTIONAL,
            'Init JSON file inside styles folder'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rootFolder = $this->rootFolder();

        $message = new Message($rootFolder);

        return $this->handler->handle($message);
    }
}
