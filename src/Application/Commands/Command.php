<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands;

use Composer\Command\BaseCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Service\ThemeJsonGenerator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends BaseCommand
{
	protected function configure()
	{
		$this->setName('theme-json')
			->setDescription('Generate theme.json file')
			->setHelp('This command generate theme.json file');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$output->writeln('Hello World');
		$io = $this->getIO();
		$composer = $this->requireComposer();

		(new ThemeJsonGenerator())($composer, $io);

		return 0;
	}
}
