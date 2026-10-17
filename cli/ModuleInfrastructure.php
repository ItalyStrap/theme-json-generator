<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli;

use ItalyStrap\Empress\AurynConfig;
use ItalyStrap\Empress\ModuleInterface;
use ItalyStrap\Finder\Finder;
use ItalyStrap\Finder\FinderFactory;
use ItalyStrap\Finder\FinderInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class ModuleInfrastructure implements ModuleInterface
{
    /**
     * @return array<string, mixed>
     */
    public function __invoke(): array
    {
        return [
            AurynConfig::ALIASES => [
                FinderInterface::class => Finder::class,
                EventDispatcherInterface::class => EventDispatcher::class,
            ],
            AurynConfig::SHARING => [
                EventDispatcherInterface::class,
                EventDispatcher::class
            ],
            AurynConfig::FACTORIES => [
                Finder::class => static fn (): FinderInterface => (new FinderFactory())->make(),
            ],
        ];
    }
}
