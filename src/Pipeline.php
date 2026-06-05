<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use Psr\Container\ContainerInterface;

final readonly class Pipeline
{
    public function __construct(
        private ContainerInterface $container,
        private ThemeJson $themeJson
    ) {
    }

    /**
     * @param iterable<class-string|callable|object> $configurators
     */
    public function process(iterable $configurators): ThemeJson
    {
        foreach ($configurators as $configurator) {
            $configurator = $this->resolveConfigurator($configurator);
            $configurator($this->themeJson);
        }

        return $this->themeJson;
    }

    /**
     * @param class-string|callable|object $configurator
     */
    private function resolveConfigurator($configurator): callable
    {
        if (\is_string($configurator) && \class_exists($configurator)) {
            $configurator = $this->container->get($configurator);
        }

        if (!\is_callable($configurator)) {
            throw new \InvalidArgumentException(\sprintf(
                'Expected configurator to be callable, got %s.',
                \get_debug_type($configurator)
            ));
        }

        return $configurator;
    }
}
