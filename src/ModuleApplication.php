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
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Handler\ConsoleHandler;
use ItalyStrap\Pipeline\MiddlewareInterface;
use Psr\Container\ContainerInterface;

class ModuleApplication implements ModuleInterface
{
    /**
     * @return array<string, array<class-string, callable(ContainerInterface): object>>
     */
    public function __invoke(): array
    {
        return [
            AurynConfig::FACTORIES => [
                InitCommand::class => function (ContainerInterface $container): InitCommand {
                    return new InitCommand(new ConsoleHandler(
                        $this->middleware($container, Init::class)
                    ));
                },
                DumpCommand::class => function (ContainerInterface $container): DumpCommand {
                    return new DumpCommand(new ConsoleHandler(
                        $this->middleware($container, Dump::class),
                    ));
                },
                ValidateCommand::class => function (ContainerInterface $container): ValidateCommand {
                    return new ValidateCommand(new ConsoleHandler(
                        new DeleteSchemaJson(),
                        new SchemaJson(),
                        $this->middleware($container, Validate::class)
                    ));
                },
                InfoCommand::class => function (ContainerInterface $container): InfoCommand {
                    return new InfoCommand(new ConsoleHandler(
                        $this->middleware($container, Info::class)
                    ));
                },
            ],
        ];
    }

    /**
     * @param class-string $id
     */
    private function middleware(ContainerInterface $container, string $id): MiddlewareInterface
    {
        $middleware = $container->get($id);
        if (!$middleware instanceof MiddlewareInterface) {
            throw new \RuntimeException(\sprintf(
                'Expected container entry %s to be an instance of %s, got %s.',
                $id,
                MiddlewareInterface::class,
                \get_debug_type($middleware)
            ));
        }

        return $middleware;
    }
}
