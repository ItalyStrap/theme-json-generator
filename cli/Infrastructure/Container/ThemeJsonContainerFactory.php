<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Container;

use Auryn\Injector;
use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\Empress\ContainerBuilder;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\ThemeJsonContainerFactoryInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetsInterface;
use Psr\Container\ContainerInterface;

final class ThemeJsonContainerFactory implements ThemeJsonContainerFactoryInterface
{
    private function create(): ContainerInterface
    {
        return (new ContainerBuilder())
            ->addModule(new ThemeJsonModule())
            ->build();
    }

    /**
     * @return ConfigInterface<array-key, mixed>
     */
    public function execute(callable $entrypoint): ConfigInterface
    {
        $container = $this->create();
        $injector = $container->get(Injector::class);

        /**
         * Injector resolves to null if a param is nullable,
         * so we need to be explicit and declare the param
         * I need this for all the classes under the Styles namespace
         */
        $injector->defineParam('presets', $injector->make(PresetsInterface::class));

        $injector->execute($entrypoint);
        $injector->execute(PresetsToThemeJson::class);
        return $container->get(ConfigInterface::class);
    }
}
