<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer;

use Composer\Command\BaseCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\InitMessage;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\DataFromJsonTrait;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\RootFolderTrait;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\EntryPointCanNotBeCreated;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\EntryPointCreated;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\EntryPointDoesNotExist;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Init;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @psalm-api
 */
class InitCommand extends BaseCommand
{
    use RootFolderTrait;
    use DataFromJsonTrait;

    public const NAME = 'init';

    private Init $init;

    private \Symfony\Component\EventDispatcher\EventDispatcher $subscriber;

    public function __construct(
        \Symfony\Component\EventDispatcher\EventDispatcher $subscriber,
        Init $init
    ) {
        $this->subscriber = $subscriber;
        $this->init = $init;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription('Initialize theme.json file');

        $this->addOption(
            'styles',
            's',
            \Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL,
            'Init JSON file inside styles folder'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rootFolder = $this->rootFolder();

        $this->subscriber->addListener(
            EntryPointDoesNotExist::class,
            static function (EntryPointDoesNotExist $event) use ($output): void {
                $output->writeln(\sprintf(
                    'Entry file does not exist, creating %s file',
                    $event->getFile()
                ));
            }
        );

        $this->subscriber->addListener(
            EntryPointCreated::class,
            static function (EntryPointCreated $event) use ($output): void {
                $output->writeln(\sprintf(
                    'Entry file %s created',
                    $event->getFile()
                ));
            }
        );

        $this->subscriber->addListener(
            EntryPointCanNotBeCreated::class,
            static function (EntryPointCanNotBeCreated $event) use ($output): void {
                $output->writeln(\sprintf(
                    'Entry file %s cannot be created because of %s',
                    $event->getFile(),
                    $event->getException()->getMessage()
                ));
            }
        );

        $message = new InitMessage($rootFolder, (string)$input->getOption('styles'));

        if ($message->getStyleOption() !== '') {
            throw new \RuntimeException('The option --styles is not yet implemented');
        }

        $this->init->handle($message);

        return Command::SUCCESS;
    }
}
