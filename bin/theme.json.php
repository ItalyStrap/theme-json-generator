<?php

declare(strict_types=1);

require $_composer_autoload_path ?? __DIR__ . '/../vendor/autoload.php';

use Composer\Console\Application;
use ItalyStrap\Config\Config;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer\DumpCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer\InitCommand;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer\ValidateCommand;

$application = new Application();
$application->add(new InitCommand());
$application->add(new DumpCommand(new Config()));
$application->add(new ValidateCommand());
$application->run();
