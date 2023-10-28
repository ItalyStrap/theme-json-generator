<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands;

use Composer\Command\BaseCommand;
use Composer\Console\Input\InputOption;
use ItalyStrap\ThemeJsonGenerator\Application\Services\ThemeJsonGenerator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends BaseCommand
{
    protected function configure()
    {
        $this->setName('theme-json')
            ->setDescription('Generate theme.json file')
            ->setHelp('This command generate theme.json file');

		// Add new flag --dry-run
		$this->addOption(
			'dry-run',
			null,
			InputOption::VALUE_NONE,
			'If set, the task will run in dry run mode.'
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

        return 0;
    }
}
