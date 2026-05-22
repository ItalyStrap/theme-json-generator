<?php

declare(strict_types=1);

$aliases = [
    // Old = New
    'ItalyStrap\ThemeJsonGenerator\Application\Config\Blueprint' => 'ItalyStrap\ThemeJsonGenerator\ThemeJson',
    'ItalyStrap\ThemeJsonGenerator\Api\ThemeJson' => 'ItalyStrap\ThemeJsonGenerator\ThemeJson',
];

foreach ($aliases as $alias => $class) {
    if (!\class_exists($alias)) {
        \class_alias($class, $alias);
    }
}
