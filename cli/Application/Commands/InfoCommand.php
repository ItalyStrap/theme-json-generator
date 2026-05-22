<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Application\Commands;

use ItalyStrap\ThemeJsonGenerator\Cli\Application\Commands\Utils\RootFolderTrait;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Message;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Handler\ConsoleHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: InfoCommand::NAME, description: InfoCommand::DESCRIPTION)]
final class InfoCommand extends Command
{
    use RootFolderTrait;

    public const NAME = 'info';

    public const DESCRIPTION = 'Show info about JSON theme';

    public function __construct(
        private ConsoleHandler $handler
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription(self::DESCRIPTION);
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
