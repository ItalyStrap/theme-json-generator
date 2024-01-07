<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Output;

use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\ThemeJsonGenerator\Application\Config\Blueprint;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Collection;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\CollectionInterface;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\JsonFileWriter;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\ScssFileWriter;
use Psr\Container\ContainerInterface;

class Dump
{
    private ConfigInterface $config;

    public function __construct(
        ConfigInterface $config
    ) {
        $this->config = $config;
    }

    /**
     * @var string
     */
    public const PATH_FOR_THEME_SASS = 'path-for-theme-sass';

    public function processBlueprint(
        callable $entryPoint,
        string $rootFolder,
        string $fileName
    ): void {
        $injector = $this->configureContainer();
        $injector->execute($entryPoint);
        $blueprint = $injector->make(Blueprint::class);

        (new JsonFileWriter($rootFolder . DIRECTORY_SEPARATOR . $fileName . '.json'))
            ->write($blueprint);

        $path_for_theme_sass = $rootFolder . DIRECTORY_SEPARATOR . $this->config->get(self::PATH_FOR_THEME_SASS);
        if (\is_writable($path_for_theme_sass)) {
            (new ScssFileWriter(
                \rtrim($path_for_theme_sass, '/') . DIRECTORY_SEPARATOR . $fileName . '.scss'
            ))->write($blueprint);
        }
    }

    public function executeCallable(
        callable $entryPoint,
        string $rootFolder,
        string $fileName
    ): void {
        $injector = $this->configureContainer();
        $injector->execute($entryPoint);
        $blueprint = $injector->make(Blueprint::class);
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
         * Injector resolve to null if a param is nullable, so we need to be explicit and declare the param
         * I need this for all the classes under the Styles namespace
         */
        $injector->defineParam('collection', $injector->make(CollectionInterface::class));

        $container = $this->createContainer($injector, clone $this->config);
        $injector->alias(ContainerInterface::class, \get_class($container));
        $injector->share($container);

        $injector->share(Blueprint::class);

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
                    throw new class (\sprintf('Service with ID %s not found.', $id)) extends \Exception implements \Psr\Container\NotFoundExceptionInterface {
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
