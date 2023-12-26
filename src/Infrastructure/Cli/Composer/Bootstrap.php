<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Infrastructure\Cli\Composer;

use Composer\Console\Application;
use ItalyStrap\Config\Config;
use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\Empress\Injector;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer\DumpCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer\InitCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer\ValidateCommand;

final class Bootstrap
{
    public function run(): int
    {
        $injector = new Injector();
        $injector->share($injector);
        $injector->alias(ConfigInterface::class, Config::class);

        $application = new Application();
        $application->add($injector->make(InitCommand::class));
        $application->add($injector->make(DumpCommand::class));
        $application->add($injector->make(ValidateCommand::class));
        $application->run();

        return 0;
    }
}
