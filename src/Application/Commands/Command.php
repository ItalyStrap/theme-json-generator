<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands;

use Composer\Command\BaseCommand;

class Command extends BaseCommand
{
	protected function configure()
	{
		$this->setName('theme-json')
			->setDescription('Generate theme.json file')
			->setHelp('This command generate theme.json file');
	}

	protected function execute($input, $output)
	{
		$output->writeln('Hello World');
	}
}
