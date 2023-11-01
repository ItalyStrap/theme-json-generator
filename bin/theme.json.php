<?php

declare(strict_types=1);

require $_composer_autoload_path ?? __DIR__ . '/../vendor/autoload.php';

use Composer\Console\Application;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer\ThemeJson;

$application = new Application();
$application->add(new ThemeJson(new \ItalyStrap\Config\Config()));
$application->run();
