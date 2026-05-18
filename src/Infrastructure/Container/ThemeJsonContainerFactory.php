<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Infrastructure\Container;

use Auryn\Injector;
use ItalyStrap\Empress\ContainerBuilder;
use ItalyStrap\ThemeJsonGenerator\Api\ThemeJson;
use ItalyStrap\ThemeJsonGenerator\Application\ThemeJsonContainerFactoryInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetsInterface;
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
     * @return ThemeJson<array-key, mixed>
     */
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
        return $container->get(ThemeJson::class);
    }
}
