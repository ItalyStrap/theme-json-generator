<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer;

use Composer\Command\BaseCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Middleware\SchemaJsonMiddleware;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\DataFromJsonTrait;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\RootFolderTrait;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\ValidateMessage;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\ValidatedFails;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\ValidatingFile;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\ValidFile;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Validate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @psalm-api
 */
class ValidateCommand extends BaseCommand
{
    use RootFolderTrait;
    use DataFromJsonTrait;

    public const NAME = 'validate';

    private Validate $validate;

    private \Symfony\Component\EventDispatcher\EventDispatcher $subscriber;

    public function __construct(
        \Symfony\Component\EventDispatcher\EventDispatcher $subscriber,
        Validate $validate
    ) {
        $this->subscriber = $subscriber;
        $this->validate = $validate;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription('Validate theme.json file');

        // $this->addOption(
        //     'force',
        //     'f',
        //     InputOption::VALUE_NONE,
        //     'Force to regenerate the theme.schema.json file'
        // );
    }

    /**
     * @todo add a rule to exclude the theme.schema.json file to .gitignore
     * @todo add a rule `--force` to regenerate the theme.schema.json file
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rootFolder = $this->rootFolder();
        $schemaPath = $rootFolder . '/theme.schema.json';

        $this->subscriber->addListener(
            ValidatingFile::class,
            static function (ValidatingFile $event) use ($output): void {
                $output->writeln('========================');
                $output->writeln(\sprintf(
                    'Validating <info>%s</info>',
                    $event->getFile()->getFilename()
                ));
            }
        );

        $this->subscriber->addListener(
            ValidFile::class,
            static function (ValidFile $event) use ($output): void {
                $output->writeln(\sprintf(
                    '<info>%s</info> is valid',
                    $event->getFile()->getFilename()
                ));
            }
        );

        $this->subscriber->addListener(
            ValidatedFails::class,
            static function (ValidatedFails $event) use ($output): void {
                $output->writeln('<error># ' . $event->getFile()->getFilename() . ' file errors</error>');
                /**
                 * @var array<string, string> $error
                 */
                foreach ($event->getErrors() as $error) {
                    $output->writeln(\sprintf(
                        '- <error>[%s]</error> is not valid. %s',
                        $error['property'] ?? '',
                        $error['message'] ?? ''
                    ));
                }
            }
        );

        $message = new ValidateMessage($rootFolder, $schemaPath);

        try {
            $schemaMiddleware = new SchemaJsonMiddleware();
            $schemaMiddleware->process($message, $this->validate);
        } catch (\Exception $exception) {
            $output->writeln('<error>Error: ' . $exception->getMessage() . '</error>');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
