<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Infrastructure\Cli\Composer;

use Composer\Console\Application;
use ItalyStrap\Config\Config;
use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\Empress\Injector;
use ItalyStrap\Finder\Finder;
use ItalyStrap\Finder\FinderFactory;
use ItalyStrap\Finder\FinderInterface;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer\DumpCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer\InitCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer\ValidateCommand;
use Psr\EventDispatcher\EventDispatcherInterface;

final class Bootstrap
{
    public function run(): int
    {
        $injector = new Injector();
        $injector->share($injector);
        $injector->alias(ConfigInterface::class, Config::class);

        $injector->alias(FinderInterface::class, Finder::class);
        $injector->delegate(Finder::class, fn (): FinderInterface => (new FinderFactory())->make());

        $injector->alias(
            EventDispatcherInterface::class,
            \Symfony\Component\EventDispatcher\EventDispatcher::class
        );
        $injector->share(EventDispatcherInterface::class);

        $application = new Application();
        $application->add($injector->make(InitCommand::class));
        $application->add($injector->make(DumpCommand::class));
        $application->add($injector->make(ValidateCommand::class));
        return $application->run();
    }
}
