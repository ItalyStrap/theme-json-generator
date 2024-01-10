<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Output;

use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\ThemeJsonGenerator\Application\Config\Blueprint;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Collection;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\CollectionInterface;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\FilesFinder;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\JsonFileWriter;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\ScssFileWriter;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class Dump
{
    private ConfigInterface $config;
    private FilesFinder $filesFinder;
    private EventDispatcherInterface $dispatcher;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        ConfigInterface $config,
        FilesFinder $filesFinder
    ) {
        $this->config = $config;
        $this->filesFinder = $filesFinder;
        $this->dispatcher = $dispatcher;
    }

    public function handle(object $command): void
    {
        /**
         * Let's test the new workflow
         * @todo add key for json file name to be created
         * @example $name => $file
         *         'theme' => 'theme.dist.json'
         */
        foreach ($this->filesFinder->find($command->getRootFolder(), 'php') as $fileName => $file) {
            // $this->dispatcher->dispatch(new GeneratingFile($file->getFilename()));
//          $folderToCreateFile = $command->getRootFolder();
//          if (\strpos((string)$file, '/styles') !== false) {
//              $folderToCreateFile .= '/styles';
//          }
//          $this->executeCallable(require $file, $folderToCreateFile, $fileName);
            $this->executeCallable(require $file, $file->getPath(), $fileName, $command, $file);
        }
    }

    private function executeCallable(
        callable $entryPoint,
        string $rootFolder,
        string $fileName,
        object $command,
        \SplFileInfo $file
    ): void {
        $injector = $this->configureContainer();
        $injector->execute($entryPoint);
        $blueprint = $injector->make(Blueprint::class);

//      var_dump($fileName);
//      var_dump($file);
//      var_dump($file->getPath() . DIRECTORY_SEPARATOR . $fileName);

//      (new JsonFileWriter($file->getPath() . DIRECTORY_SEPARATOR . $fileName . '.test.json'))
//          ->write($blueprint);

        if ($command->isDryRun()) {
            // Add event the command is in dry run mode
            // $this->dispatcher->dispatch(new DryRunMode(''));
            return;
        }
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
