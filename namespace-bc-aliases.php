<?php

declare(strict_types=1);

use ItalyStrap\ThemeJsonGenerator\Application\Config\Blueprint;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

$aliases = [
    // Old = New
    Blueprint::class => ThemeJson::class,
    \ItalyStrap\ThemeJsonGenerator\Api\ThemeJson::class => ThemeJson::class,
];

foreach ($aliases as $alias => $class) {
    if (!\class_exists($alias)) {
        \class_alias($class, $alias);
    }
}
