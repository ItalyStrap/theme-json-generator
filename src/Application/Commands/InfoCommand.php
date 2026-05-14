<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands;

use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\RootFolderTrait;
use ItalyStrap\ThemeJsonGenerator\Application\Message;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @psalm-api
 */
#[AsCommand(name: InfoCommand::NAME, description: InfoCommand::DESCRIPTION)]
class InfoCommand extends Command
{
    use RootFolderTrait;

    public const NAME = 'info';
    public const DESCRIPTION = 'Show info about JSON theme';

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
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rootFolder = $this->rootFolder();

        $message = new Message($rootFolder);

        try {
            return (int)$this->handler->handle($message);
        } catch (\Exception $exception) {
            $output->writeln('<error>Error: ' . $exception->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
