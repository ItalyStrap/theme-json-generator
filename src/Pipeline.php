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
     * @param iterable<array-key, string|ConfiguratorInterface> $configurators
     */
    public function process(iterable $configurators): void
    {
        foreach ($configurators as $configurator) {
            $configurator = $this->resolveConfigurator($configurator);
            $configurator($this->themeJson);
        }

        return;
    }

    /**
     * @param string|ConfiguratorInterface $configurator
     */
    private function resolveConfigurator(string|ConfiguratorInterface $configurator): ConfiguratorInterface
    {
        if ($configurator instanceof ConfiguratorInterface) {
            return $configurator;
        }

        if (!\is_a($configurator, ConfiguratorInterface::class, true)) {
            throw new \InvalidArgumentException(\sprintf(
                'Expected configurator class-string to implement ConfiguratorInterface, got %s.',
                $configurator
            ));
        }

        return $this->container->get($configurator);
    }
}
