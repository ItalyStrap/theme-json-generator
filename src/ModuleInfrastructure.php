<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use ItalyStrap\Empress\AurynConfig;
use ItalyStrap\Finder\Finder;
use ItalyStrap\Finder\FinderFactory;
use ItalyStrap\Finder\FinderInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class ModuleInfrastructure implements \ItalyStrap\Empress\ModuleInterface
{
    public function __invoke(): array
    {
        return [
            AurynConfig::ALIASES => [
                FinderInterface::class => Finder::class,
                EventDispatcherInterface::class => \Symfony\Component\EventDispatcher\EventDispatcher::class,
            ],
            AurynConfig::SHARING => [
                EventDispatcherInterface::class,
                \Symfony\Component\EventDispatcher\EventDispatcher::class
            ],
            AurynConfig::FACTORIES => [
                Finder::class => static fn (): FinderInterface => (new FinderFactory())->make(),
            ],
        ];
    }
}