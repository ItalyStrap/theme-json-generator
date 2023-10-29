<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer;

use Composer\Command\BaseCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Services\ThemeJsonGenerator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class ThemeJson extends BaseCommand
{
    public const NAME = 'theme-json';

    protected static $defaultName = 'theme-json';
    protected static $defaultDescription = 'Generate theme.json file';

    protected function configure()
    {
        $this->setName(static::NAME)
            ->setDescription('Generate theme.json file')
            ->setHelp('This command generate theme.json file');

        // Add new flag --dry-run
        $this->addOption(
            'dry-run',
            null,
            InputOption::VALUE_NONE,
            'If set, ' . static::NAME . ' will run in dry run mode.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Start generating theme.json file');
        $io = $this->getIO();
        $composer = $this->requireComposer();

        // Get the value of --dry-run
        $dry_run = $input->getOption('dry-run');
         var_dump($dry_run);

        (new ThemeJsonGenerator())($composer, $io);

        $output->writeln('End generating theme.json file');

        return ThemeJson::SUCCESS;
    }
}
