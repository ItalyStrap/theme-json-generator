<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use ItalyStrap\Empress\ContainerBuilder;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\DumpCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\InfoCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\InitCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\ValidateCommand;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;

final class Bootstrap
{
    public function container(): ContainerInterface
    {
        $builder = new ContainerBuilder();

        /**
         * The order of the modules is important
         */
//        $builder->addModule(new ModuleUI());
        $builder->addModule(new ModuleInfrastructure());
//        $builder->addModule(new ModuleDomain());
        $builder->addModule(new ModuleApplication());

        return $builder->build();
    }
    public function run(): int
    {
        $container = $this->container();

        $application = new Application('Theme JSON Generator', '0.1.0');

        $commandLoader = new ContainerCommandLoader($container, [
            InitCommand::NAME => InitCommand::class,
            DumpCommand::NAME => DumpCommand::class,
            ValidateCommand::NAME => ValidateCommand::class,
            InfoCommand::NAME => InfoCommand::class,
        ]);

        $application->setCommandLoader($commandLoader);

        return $application->run();
    }
}
