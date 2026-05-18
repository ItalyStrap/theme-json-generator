<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Middlewares;

use ItalyStrap\Config\Config;
use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\Pipeline\MiddlewareInterface;
use ItalyStrap\ThemeJsonGenerator\Api\ThemeJson;
use ItalyStrap\ThemeJsonGenerator\Application\DumpMessage;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetsInterface;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\FilesFinder;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\JsonFileWriter;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\ScssFileWriter;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @psalm-api
 */
class Dump implements MiddlewareInterface
{
    /**
     * @var string
     */
    public const M_NO_FILE_FOUND = 'No file found';

    public const JSON_FILE_SUFFIX = '.json';

    private FilesFinder $filesFinder;

    public function __construct(
        FilesFinder $filesFinder
    ) {
        $this->filesFinder = $filesFinder;
    }

    public function process(object $message, HandlerInterface $handler): int
    {
        /**
         * OutputInterface $output
         */
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();

        $count = 0;
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
            $themeJson = $injector->make(ThemeJson::class);
            $themeJson->setPresets($presets);
            $count++;

            /**
             * @todo Add subscription configuration.
             */
//            $dispatcher = $injector->make(EventDispatcherInterface::class);
//            $dispatcher->dispatch($themeJson);

            if ($message->isDryRun()) {
                $output->writeln(\sprintf(
                    '<comment>Dry run mode enabled, skipping file generation for %s</comment>',
                    $fileName
                ));
                continue;
            }

            $this->generateJsonFile($output, $message, $fileName, $file, $themeJson);
            $this->generateScssFile($output, $message, $fileName, $themeJson);
        }

        if ($count === 0) {
            $output->writeln(self::M_NO_FILE_FOUND);
        }

        return (int)$handler->handle($message);
    }

    private function generateJsonFile(
        OutputInterface $output,
        DumpMessage $message,
        string $fileName,
        \SplFileInfo $file,
        ThemeJson $themeJson
    ): void {

        $output->writeln(\sprintf(
            '<info>Generating %s file</info>',
            $fileName . self::JSON_FILE_SUFFIX
        ));

        (new JsonFileWriter($this->filesFinder->resolveJsonFile($file)))
            ->write($themeJson);

        $output->writeln(\sprintf(
            '<info>Generated %s file</info>',
            $fileName . self::JSON_FILE_SUFFIX
        ));
        $output->writeln('========================');
    }

    private function generateScssFile(
        OutputInterface $output,
        DumpMessage $message,
        string $fileName,
        ThemeJson $themeJson
    ): void {
        $path_for_theme_sass = $message->getRootFolder() . DIRECTORY_SEPARATOR . $message->getSassFolder();
        if ($message->getSassFolder() !== '' && \is_writable($path_for_theme_sass)) {
            $output->writeln(\sprintf(
                '<info>Generating %s file</info>',
                $fileName . '.scss'
            ));

            (new ScssFileWriter($path_for_theme_sass . DIRECTORY_SEPARATOR . $fileName . '.scss'))
                ->write($themeJson);

            $output->writeln(\sprintf(
                '<info>Generated %s file</info>',
                $fileName . '.scss'
            ));
            $output->writeln('========================');
        }
    }

    private function configureContainer(): \Auryn\Injector
    {
        $injector = new \Auryn\Injector();
        $injector->share($injector);

        $container = $this->createContainer($injector, new Config());
        $injector->alias(ContainerInterface::class, \get_class($container));
        $injector->share($container);

        $injector->alias(PresetsInterface::class, Presets::class);
        $injector->share(PresetsInterface::class);

        /**
         * Injector resolve to null if a param is nullable, so we need to be explicit and declare the param
         * I need this for all the classes under the Styles namespace
         */
        $injector->defineParam('presets', $injector->make(PresetsInterface::class));

        $injector->share(ThemeJson::class);

        return $injector;
    }

    private function createContainer(
        \Auryn\Injector $injector,
        \ItalyStrap\Config\ConfigInterface $config
    ): ContainerInterface {
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
