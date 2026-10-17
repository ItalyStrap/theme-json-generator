<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Application\Commands;

use ItalyStrap\ThemeJsonGenerator\Cli\Application\Commands\Utils\RootFolderTrait;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Message;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Handler\ConsoleHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class WorkflowCommand extends Command
{
    use RootFolderTrait;

    public const NAME = 'workflow:init';

    public function __construct(private readonly ConsoleHandler $handler)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription('Copy a customizable GitHub Actions workflow into the theme project once.');
        $this->addOption(
            'path',
            'p',
            InputOption::VALUE_REQUIRED,
            'Theme directory; defaults to the working directory.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getOption('path');
        $message = new Message($this->rootFolder(\is_string($path) ? $path : ''));

        return $this->handler->handle($message);
    }
}
