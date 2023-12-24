<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer;

use Composer\Command\BaseCommand;
use Composer\Composer as BaseComposer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\FilesFinderTrait;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils\RootFolderTrait;
use ItalyStrap\ThemeJsonGenerator\Domain\SectionNames;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Collection;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\CollectionInterface;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\JsonFileWriter;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\ScssFileWriter;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @psalm-api
 */
final class DumpCommand extends BaseCommand
{
    use RootFolderTrait;
    use FilesFinderTrait;

    public const NAME = 'dump';

//    protected static $defaultName = 'dump';
//    protected static $defaultDescription = 'Generate theme.json file';
    private ConfigInterface $config;

    public function __construct(ConfigInterface $config)
    {
        parent::__construct();
        $this->config = $config;
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription('Generate theme.json file');
        $this->setHelp('This command generate theme.json file');

        $this->addOption(
            'dry-run',
            null,
            InputOption::VALUE_NONE,
            \sprintf(
                'If set, %s will run in dry run mode.',
                self::NAME
            )
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getIO();
        $composer = $this->requireComposer();
        $rootFolder = $this->rootFolder();

        // Get the value of --dry-run
        $dry_run = $input->getOption('dry-run');

        $output->writeln('<info>Generating theme.json file</info>');
        $this->process($composer, $io, $rootFolder);
        $output->writeln('<info>Generated theme.json file</info>');

        return Command::SUCCESS;
    }

    public const COMPOSER_EXTRA_THEME_JSON_KEY = 'theme-json';
    public const CALLBACK = 'callable';
    public const PATH_FOR_THEME_SASS = 'path-for-theme-sass';

    private function process(BaseComposer $composer, IOInterface $io, string $rootFolder): void
    {
        $package = $composer->getPackage();
        $this->config->merge($package->getExtra()[self::COMPOSER_EXTRA_THEME_JSON_KEY] ?? []);

        $callback = $this->config->get(self::CALLBACK);
        if (!is_callable($callback)) {
            throw new \RuntimeException(\sprintf(
                'Maybe the %s is not a valid callable',
                $callback
            ));
        }

        try {
            $result = (array)$callback();
            $data = clone $this->config;
            $data->merge($result);
            ( new JsonFileWriter($rootFolder . '/theme.json') )
                ->write($data);

            $path_for_theme_sass = $rootFolder . '/' . $this->config->get(self::PATH_FOR_THEME_SASS);
            if (\is_writable($path_for_theme_sass)) {
                ( new ScssFileWriter(
                    \rtrim($path_for_theme_sass, '/') . '/theme.scss'
                ) )->write($data);
            }
        } catch (\Exception $e) {
            $io->write($e->getMessage());
        }

        /**
         * Let's test the new workflow
         */
        foreach ($this->findPhpFiles($rootFolder) as $file) {
            $this->testNewWorkflow($file, $io);
        }
    }

    private function testNewWorkflow(string $fileInput, IOInterface $io)
    {
        $injector = new \Auryn\Injector();
        $collection = $injector->make(Collection::class);
        $container = $this->createContainer($injector, clone $this->config);
        $injector->share($container);
        $injector->share($injector);
        $injector->alias(ContainerInterface::class, \get_class($container));
        $injector->alias(CollectionInterface::class, Collection::class);

        $data = clone $this->config;
        $data->merge((array)$injector->execute(require $fileInput));
        var_dump($data);
    }

    private function createContainer($injector, $config): ContainerInterface
    {
        return new class ($injector, $config) implements ContainerInterface {
            private \Auryn\Injector $injector;
            private ConfigInterface $config;

            public function __construct(\Auryn\Injector $injector, ConfigInterface $config)
            {
                $this->injector = $injector;
                $this->config = $config;
            }

            public function get(string $id)
            {
                if (!$this->has($id)) {
                    throw new class ("Service with ID {$id} not found.") extends \Exception implements \Psr\Container\NotFoundExceptionInterface {
                    };
                }

                return $this->config->get($id, $this->injector->make($id));
            }

            public function has(string $id): bool
            {
                return $this->config->has($id) || \class_exists($id) || $this->injectorHas($id);
            }

            private function injectorHas(string $id): bool
            {
                $details = $this->injector->inspect($id, 31);
                return (bool) array_filter($details);
            }
        };
    }
}
