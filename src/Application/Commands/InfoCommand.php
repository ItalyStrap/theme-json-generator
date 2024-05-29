<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands;

use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\RootFolderTrait;
use \ItalyStrap\ThemeJsonGenerator\Application\InfoMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @psalm-api
 */
class InfoCommand extends Command
{
    use RootFolderTrait;

    public const NAME = 'info';

    private \ItalyStrap\Bus\Bus $bus;

    public function __construct(
        \ItalyStrap\Bus\Bus $bus
    ) {
        $this->bus = $bus;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription('Show info about JSON theme');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rootFolder = $this->rootFolder();

        $message = new InfoMessage($rootFolder);

        try {
            return (int)$this->bus->handle($message);
        } catch (\Exception $exception) {
            $output->writeln('<error>Error: ' . $exception->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
