<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer;

use Composer\Command\BaseCommand;
use Composer\Composer as BaseComposer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use ItalyStrap\Config\Config;
use ItalyStrap\Config\ConfigInterface;
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
use Opis\JsonSchema\{
    Validator,
    ValidationResult,
    Errors\ErrorFormatter,
};

/**
 * @psalm-api
 */
final class ThemeJson extends BaseCommand
{
    /**
     * @var string
     */
    public const NAME = 'theme-json';

    protected static $defaultName = 'theme-json';

    protected static $defaultDescription = 'Generate theme.json file';

    private ConfigInterface $config;

    public function __construct(ConfigInterface $config)
    {
        parent::__construct();
        $this->config = $config;
    }

    protected function configure(): void
    {
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

        // Get the value of --dry-run
        $dry_run = $input->getOption('dry-run');

        $output->writeln('Start generating theme.json file');
        $this->process($composer, $io);
        $output->writeln('End generating theme.json file');

        return Command::SUCCESS;
    }

    /**
     * @var string
     */
    public const COMPOSER_EXTRA_THEME_JSON_KEY = 'theme-json';

    /**
     * @var string
     */
    public const PACKAGE_TYPE = 'wordpress-theme';

    /**
     * @var string
     */
    public const CALLBACK = 'callable';

    private function process(BaseComposer $composer, IOInterface $io): void
    {
        $rootPackage = $composer->getPackage();

        /** @var string $vendorPath */
        $vendorPath = $composer->getConfig()->get('vendor-dir');
        $rootPackagePath = dirname($vendorPath);

        if ($rootPackage->getType() === self::PACKAGE_TYPE) {
            $this->writeFile($rootPackage, $rootPackagePath, $io);
            return;
        }

        $repo = $composer->getRepositoryManager();
        foreach ($rootPackage->getRequires() as $link) {
            $constraint = $link->getConstraint();
            $package = $repo->findPackage($link->getTarget(), $constraint);
            $packagePath = $vendorPath . '/' . $link->getTarget();
            if (!$package instanceof \Composer\Package\PackageInterface) {
                continue;
            }

            if ($package->getType() !== self::PACKAGE_TYPE) {
                continue;
            }

            $this->writeFile($package, $packagePath, $io);
        }
    }

    private function writeFile(PackageInterface $package, string $path, IOInterface $io): void
    {
        $this->config->merge($package->getExtra()[self::COMPOSER_EXTRA_THEME_JSON_KEY] ?? []);

        $callback = $this->config->get(self::CALLBACK);
        if (!is_callable($callback)) {
            throw new \RuntimeException(\sprintf(
                'Maybe the %s is not a valid callable',
                $callback
            ));
        }

        /**
         * @todo some alternative to file name:
         *       - theme.php
         *       - theme.json.php
         *       - theme.json.dist.php
         */
        $fileInput = $path . '/theme.php';

        if (!\file_exists($fileInput) || !\is_readable($fileInput)) {
            $io->write(\sprintf(
                'File %s not found or not readable',
                $fileInput
            ));
            return;
        }

        $validator = new Validator();


        $injector = new \Auryn\Injector();
        $collection = $injector->make(Collection::class);
        $container = $this->createContainer($injector, clone $this->config);
        $injector->share($container);
        $injector->share($injector);
        $injector->alias(ContainerInterface::class, \get_class($container));
        $injector->alias(CollectionInterface::class, Collection::class);

        $data = clone $this->config;
        $data->merge((array)$injector->execute(require $fileInput));

        if ($data->get(SectionNames::VERSION) > 1) {
            // Search all php files in the `styles` directory using Glob
            $files = \glob($path . '/styles/*.php');
//            var_dump($files);
        }

        try {
            $result = (array)$callback();
            $data = clone $this->config;
            $data->merge($result);
            ( new JsonFileWriter($path . '/theme.json') )
                ->write($data);

            $path_for_theme_sass = $path . '/' . $this->config->get('path-for-theme-sass');
            if (\is_writable($path_for_theme_sass)) {
                ( new ScssFileWriter(
                    \rtrim($path_for_theme_sass, '/') . '/theme.scss'
                ) )->write($data);
            }
        } catch (\Exception $exception) {
            $io->write($exception->getMessage());
        }
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
                return (bool) array_filter($details);
            }
        };
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function getDefaultExtra(): array
    {
        return [
            'theme-json' => [
                self::CALLBACK => false,
                'path-for-theme-sass'   => '',
            ],
        ];
    }
}
