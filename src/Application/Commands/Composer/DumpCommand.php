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

    /**
     * @var string
     */
    public const NAME = 'dump';

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

    /**
     * @var string
     */
    public const COMPOSER_EXTRA_THEME_JSON_KEY = 'theme-json';

    /**
     * @var string
     */
    public const CALLBACK = 'callable';

    /**
     * @var string
     */
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
            $injector = $this->configureContainer();
            $result = (array)$injector->execute($callback);
            $data = clone $this->config;
            $data->merge($result);
            (new JsonFileWriter($rootFolder . '/theme.json'))
                ->write($data);

            $path_for_theme_sass = $rootFolder . '/' . $this->config->get(self::PATH_FOR_THEME_SASS);
            if (\is_writable($path_for_theme_sass)) {
                (new ScssFileWriter(
                    \rtrim($path_for_theme_sass, '/') . '/theme.scss'
                ))->write($data);
            }
        } catch (\Exception $exception) {
            $io->write($exception->getMessage());
        }

        /**
         * Let's test the new workflow
         */
        foreach ($this->findPhpFiles($rootFolder) as $file) {
            $this->executeCallable((string)$file);
        }
    }

    private function executeCallable(string $fileInput): void
    {
        $injector = $this->configureContainer();
        $injector->execute(require $fileInput);
    }

    /**
     * @return \Auryn\Injector
     * @throws \Auryn\ConfigException
     * @throws \Auryn\InjectionException
     */
    private function configureContainer(): \Auryn\Injector
    {
        $injector = new \Auryn\Injector();
        $injector->share($injector);

        $injector->alias(CollectionInterface::class, Collection::class);
        $injector->share(CollectionInterface::class);
        /**
         * @todo Find out why I need to declare `collection` param in the hardcoded way
         *       I needed this for all Styles classes that need to be injected with the shared collection
         */
        $injector->defineParam('collection', $injector->make(CollectionInterface::class));

        $container = $this->createContainer($injector, clone $this->config);
        $injector->alias(ContainerInterface::class, \get_class($container));
        $injector->share($container);

        return $injector;
    }

    private function createContainer(\Auryn\Injector $injector, \ItalyStrap\Config\ConfigInterface $config): ContainerInterface
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
                    throw new class (sprintf('Service with ID %s not found.', $id)) extends \Exception implements \Psr\Container\NotFoundExceptionInterface {
                    };
                }

                return $this->config->get($id, $this->injector->make($id));
            }

            public function has(string $id): bool
            {
                if ($this->config->has($id)) {
                    return true;
                }

                if (\class_exists($id)) {
                    return true;
                }

                return $this->injectorHas($id);
            }

            private function injectorHas(string $id): bool
            {
                $details = $this->injector->inspect($id, 31);
                return (bool) \array_filter($details);
            }
        };
    }
}
