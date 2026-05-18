<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use ItalyStrap\Empress\AurynConfig;
use ItalyStrap\Empress\ModuleInterface;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\DumpCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\InfoCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\InitCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\ValidateCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Middlewares\DeleteSchemaJson;
use ItalyStrap\ThemeJsonGenerator\Application\Middlewares\Dump;
use ItalyStrap\ThemeJsonGenerator\Application\Middlewares\Info;
use ItalyStrap\ThemeJsonGenerator\Application\Middlewares\Init;
use ItalyStrap\ThemeJsonGenerator\Application\Middlewares\SchemaJson;
use ItalyStrap\ThemeJsonGenerator\Application\Middlewares\Validate;
use ItalyStrap\ThemeJsonGenerator\Application\ThemeJsonContainerFactoryInterface;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Container\ThemeJsonContainerFactory;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Handler\ConsoleHandler;
use Psr\Container\ContainerInterface;

class ModuleApplication implements ModuleInterface
{
    /**
     * @return array<string, mixed>
     */
    public function __invoke(): array
    {
        return [
            AurynConfig::ALIASES => [
                ThemeJsonContainerFactoryInterface::class => ThemeJsonContainerFactory::class,
            ],
            AurynConfig::FACTORIES => [
                InitCommand::class => function (ContainerInterface $container): InitCommand {
                    return new InitCommand(new ConsoleHandler(
                        $container->get(Init::class)
                    ));
                },
                DumpCommand::class => function (ContainerInterface $container): DumpCommand {
                    return new DumpCommand(new ConsoleHandler(
                        $container->get(Dump::class),
                    ));
                },
                ValidateCommand::class => function (ContainerInterface $container): ValidateCommand {
                    return new ValidateCommand(new ConsoleHandler(
                        new DeleteSchemaJson(),
                        new SchemaJson(),
                        $container->get(Validate::class)
                    ));
                },
                InfoCommand::class => function (ContainerInterface $container): InfoCommand {
                    return new InfoCommand(new ConsoleHandler(
                        $container->get(Info::class)
                    ));
                },
            ],
        ];
    }
}
