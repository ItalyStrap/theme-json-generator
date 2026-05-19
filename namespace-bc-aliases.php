<?php

declare(strict_types=1);

$aliases = [
    'ItalyStrap\ThemeJsonGenerator\Api\ThemeJson' => 'ItalyStrap\ThemeJsonGenerator\Application\Config\Blueprint',
];

foreach ($aliases as $class => $alias) {
    if (!\class_exists($alias)) {
        \class_alias($class, $alias);
    }
}
