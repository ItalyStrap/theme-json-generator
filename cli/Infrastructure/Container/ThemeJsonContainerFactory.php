<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Container;

use Auryn\Injector;
use ItalyStrap\Empress\ContainerBuilder;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\ThemeJsonContainerFactoryInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetsInterface;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;
use Psr\Container\ContainerInterface;

final class ThemeJsonContainerFactory implements ThemeJsonContainerFactoryInterface
{
    private function create(): ContainerInterface
    {
        return (new ContainerBuilder())
            ->addModule(new ThemeJsonModule())
            ->build();
    }

    public function execute(callable $entrypoint): ThemeJson
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

        $themeJson = $container->get(ThemeJson::class);
        $container->get(PresetsToThemeJson::class)($themeJson, $container->get(PresetsInterface::class));

        return $themeJson;
    }
}
