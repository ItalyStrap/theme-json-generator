<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use ItalyStrap\ThemeJsonGenerator\Infrastructure\Cli\Composer\Bootstrap;

require $_composer_autoload_path ?? __DIR__ . '/../vendor/autoload.php';

$bootstrap = new Bootstrap();
exit($bootstrap->run());
