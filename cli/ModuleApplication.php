<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli;

use ItalyStrap\Empress\AurynConfig;
use ItalyStrap\Empress\ModuleInterface;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Commands\DumpCommand;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Commands\InfoCommand;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Commands\InitCommand;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Commands\ScssCommand;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Commands\ValidateCommand;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Commands\WorkflowCommand;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Middlewares\CopyWorkflowTemplate;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Middlewares\DeleteSchemaJson;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Middlewares\Dump;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Middlewares\GenerateScss;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Middlewares\GenerateThemeJsonConfigFileFromThemeJson;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Middlewares\Info;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Middlewares\SchemaJson;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Middlewares\Validate;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\ThemeJsonContainerFactoryInterface;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Container\ThemeJsonContainerFactory;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Handler\ConsoleHandler;
use Psr\Container\ContainerInterface;

final class ModuleApplication implements ModuleInterface
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
                ScssCommand::class
                    => fn(ContainerInterface $container): ScssCommand
                        => new ScssCommand(new ConsoleHandler(
                            $container->get(GenerateScss::class)
                        )),
                WorkflowCommand::class
                    => fn(ContainerInterface $container): WorkflowCommand
                        => new WorkflowCommand(new ConsoleHandler(
                            $container->get(CopyWorkflowTemplate::class)
                        )),
                InitCommand::class
                    => fn(ContainerInterface $container): InitCommand
                        => new InitCommand(new ConsoleHandler(
                            $container->get(GenerateThemeJsonConfigFileFromThemeJson::class)
                        )),
                DumpCommand::class
                    => fn(ContainerInterface $container): DumpCommand
                        => new DumpCommand(new ConsoleHandler(
                            $container->get(Dump::class),
                        )),
                ValidateCommand::class
                    => fn(ContainerInterface $container): ValidateCommand
                        => new ValidateCommand(new ConsoleHandler(
                            new DeleteSchemaJson(),
                            new SchemaJson(),
                            $container->get(Validate::class)
                        )),
                InfoCommand::class
                    => fn(ContainerInterface $container): InfoCommand
                        => new InfoCommand(new ConsoleHandler(
                            $container->get(Info::class)
                        )),
            ],
        ];
    }
}
