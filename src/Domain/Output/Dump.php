<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Output;

use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\DumpMessage;
use ItalyStrap\ThemeJsonGenerator\Application\Config\Blueprint;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetsInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\DryRunMode;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\GeneratedFile;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\GeneratingFile;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\FilesFinder;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\JsonFileWriter;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\ScssFileWriter;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @psalm-api
 */
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

    public function handle(DumpMessage $message): void
    {
        /**
         * Let's test the new workflow
         * @example $name => $file
         *         'theme' => 'theme.json'
         */
        foreach ($this->filesFinder->find($message->getRootFolder(), 'php') as $fileName => $file) {
            $injector = $this->configureContainer();
            /** @psalm-suppress UnresolvableInclude */
            $injector->execute(require $file);
            $presets = $injector->make(PresetsInterface::class);
            $blueprint = $injector->make(Blueprint::class);
            $blueprint->setPresets($presets);

            /**
             * @todo Add subscription configuration.
             */
//            $dispatcher = $injector->make(EventDispatcherInterface::class);
//            $dispatcher->dispatch($blueprint);

            if ($message->isDryRun()) {
                $this->dispatcher->dispatch(new DryRunMode());
                continue;
            }

            $this->generateJsonFile($message, $fileName, $file, $blueprint);
            $this->generateScssFile($message, $fileName, $blueprint);
        }
    }

    private function generateJsonFile(
        DumpMessage $command,
        string $fileName,
        \SplFileInfo $file,
        Blueprint $blueprint
    ): void {
        $this->dispatcher->dispatch(new GeneratingFile($fileName . '.test.json'));

        (new JsonFileWriter($file->getPath() . DIRECTORY_SEPARATOR . $fileName . '.test.json'))
            ->write($blueprint);

        $this->dispatcher->dispatch(new GeneratedFile($fileName . '.test.json'));
    }

    private function generateScssFile(DumpMessage $command, string $fileName, Blueprint $blueprint): void
    {
        $this->dispatcher->dispatch(new GeneratingFile($fileName . '.scss'));

        $path_for_theme_sass = $command->getRootFolder() . DIRECTORY_SEPARATOR . $command->getSassFolder();
        if ($command->getSassFolder() !== '' && \is_writable($path_for_theme_sass)) {
            (new ScssFileWriter($path_for_theme_sass . DIRECTORY_SEPARATOR . $fileName . '.scss'))
                ->write($blueprint);
            $this->dispatcher->dispatch(new GeneratedFile($fileName . '.scss'));
        }
    }

    public function processBlueprint(
        DumpMessage $message,
        string $fileName,
        callable $entryPoint
    ): void {
        $injector = $this->configureContainer();
        $injector->execute($entryPoint);

        $collection = $injector->make(PresetsInterface::class);
        $blueprint = $injector->make(Blueprint::class);
        $blueprint->setPresets($collection);

        (new JsonFileWriter($message->getRootFolder() . DIRECTORY_SEPARATOR . $fileName . '.json'))
            ->write($blueprint);
    }

    private function configureContainer(): \ItalyStrap\Empress\Injector
    {
        $injector = new \ItalyStrap\Empress\Injector();
        $injector->share($injector);

        $container = $this->createContainer($injector, clone $this->config);
        $injector->alias(ContainerInterface::class, \get_class($container));
        $injector->share($container);

        $injector->alias(PresetsInterface::class, Presets::class);
        $injector->share(PresetsInterface::class);

        $injector->alias(EventDispatcherInterface::class, \get_class($this->dispatcher));
        $injector->share(EventDispatcherInterface::class);

        /**
         * Injector resolve to null if a param is nullable, so we need to be explicit and declare the param
         * I need this for all the classes under the Styles namespace
         */
        $injector->defineParam('collection', $injector->make(PresetsInterface::class));

        $injector->share(Blueprint::class);

        return $injector;
    }

    private function createContainer(
        \ItalyStrap\Empress\Injector $injector,
        \ItalyStrap\Config\ConfigInterface $config
    ): ContainerInterface {
        return new class ($injector, $config) implements ContainerInterface {
            private \Auryn\Injector $injector;

            private ConfigInterface $config;

            public function __construct(\ItalyStrap\Empress\Injector $injector, ConfigInterface $config)
            {
                $this->injector = $injector;
                $this->config = $config;
            }

            public function get(string $id)
            {
                if (!$this->has($id)) {
                    throw new class (\sprintf(
                        'Service with ID %s not found.',
                        $id
                    )) extends \Exception implements \Psr\Container\NotFoundExceptionInterface {
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
