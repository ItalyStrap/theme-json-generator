<?php

declare(strict_types=1);

require $_composer_autoload_path ?? __DIR__.'/../vendor/autoload.php';

use Composer\Console\Application;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer;

$application = new Application();
$application->add(new Composer());
$application->run();
