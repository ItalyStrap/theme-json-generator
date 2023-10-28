<?php

declare(strict_types=1);

use ItalyStrap\ThemeJsonGenerator\Application\Commands\ThemeJson;

require $_composer_autoload_path ?? __DIR__.'/../vendor/autoload.php';

global $argv;
array_shift($argv);
(new ThemeJson())($argv);
