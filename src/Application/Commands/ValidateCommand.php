<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands;

use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\RootFolderTrait;
use ItalyStrap\ThemeJsonGenerator\Application\ValidateMessage;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\DataFromJsonTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @psalm-api
 */
#[AsCommand(name: ValidateCommand::NAME, description: ValidateCommand::DESCRIPTION)]
class ValidateCommand extends Command
{
    use RootFolderTrait;
    use DataFromJsonTrait;

    public const NAME = 'validate';
    public const DESCRIPTION = 'Validate theme.json file';
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

         $this->addOption(
             'force',
             'f',
             InputOption::VALUE_NONE,
             'Force to regenerate the theme.schema.json file'
         );
    }

    /**
     * @todo add a rule to exclude the theme.schema.json file to .gitignore
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rootFolder = $this->rootFolder();
        $schemaPath = $rootFolder . '/theme.schema.json';

        $message = new ValidateMessage($rootFolder, $schemaPath, (bool)$input->getOption('force'));

        try {
            return (int)$this->handler->handle($message);
        } catch (\Exception $exception) {
            $output->writeln('<error>Error: ' . $exception->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
