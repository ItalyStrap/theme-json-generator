<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Application\Commands;

use ItalyStrap\ThemeJsonGenerator\Cli\Application\ScssMessage;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Handler\ConsoleHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class ScssCommand extends Command
{
    public const NAME = 'scss';

    public function __construct(private readonly ConsoleHandler $handler)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription('Generate SCSS references from an existing theme.json or style variation.');
        $this->addOption('input', null, InputOption::VALUE_REQUIRED, 'JSON file to read.', 'theme.json');
        $this->addOption(
            'output',
            null,
            InputOption::VALUE_REQUIRED,
            'SCSS file; defaults to _<input-name>.scss beside input.'
        );
        $this->addOption('dry-run', null, InputOption::VALUE_NONE, 'Report the destination without writing.');
        $this->addOption('check', null, InputOption::VALUE_NONE, 'Check output freshness without writing.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getOption('check') && $input->getOption('dry-run')) {
            throw new \InvalidArgumentException('Use either --check or --dry-run.');
        }

        $source = $input->getOption('input');
        $destination = $input->getOption('output');

        return $this->handler->handle(new ScssMessage(
            \is_string($source) ? $source : 'theme.json',
            \is_string($destination) ? $destination : null,
            $input->getOption('dry-run') === true,
            $input->getOption('check') === true
        ));
    }
}
