<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use Symfony\Component\Console\Application;
use ItalyStrap\Bus\Bus;
use ItalyStrap\Config\Config;
use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\Empress\Injector;
use ItalyStrap\Finder\Finder;
use ItalyStrap\Finder\FinderFactory;
use ItalyStrap\Finder\FinderInterface;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\DumpCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\InfoCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\InitCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\ValidateCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Middleware\DeleteSchemaJsonMiddleware;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Middleware\SchemaJsonMiddleware;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Info;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Validate;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @psalm-api
 */
final class Bootstrap
{
    public function run(): int
    {
        $injector = new Injector();
        $injector->share($injector);
        $injector->alias(ConfigInterface::class, Config::class);

        $injector->alias(FinderInterface::class, Finder::class);
        $injector->delegate(Finder::class, static fn (): FinderInterface => (new FinderFactory())->make());

        $injector->alias(
            EventDispatcherInterface::class,
            \Symfony\Component\EventDispatcher\EventDispatcher::class
        );
        $injector->share(EventDispatcherInterface::class);
        $injector->share(\Symfony\Component\EventDispatcher\EventDispatcher::class);

        $application = new Application();
        /** @psalm-suppress InvalidArgument */
        $application->add($injector->make(InitCommand::class));
        /** @psalm-suppress InvalidArgument */
        $application->add($injector->make(DumpCommand::class));
        /** @psalm-suppress InvalidArgument */
        $application->add($injector->make(ValidateCommand::class, [
            '+handler' => static function (string $named_param, Injector $injector): Bus {
                $bus = new Bus(
                    $injector->make(Validate::class)
                );
                $bus->addMiddleware(
                    new DeleteSchemaJsonMiddleware(),
                    new SchemaJsonMiddleware()
                );
                return $bus;
            },
        ]));
        $application->add($injector->make(InfoCommand::class, [
            '+handler' => static fn(string $named_param, Injector $injector): Bus => new Bus(
                $injector->make(Info::class)
            ),
        ]));
        return $application->run();
    }
}
