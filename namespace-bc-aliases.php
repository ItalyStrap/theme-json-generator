<?php

/**
 * @todo Remove this after the commit is pushed
 */

declare(strict_types=1);

\spl_autoload_register(function (string $class): void {
    $prefix = 'ItalyStrap\\Bus\\';
    $base_dir = __DIR__ . '/bus/';
    $len = \strlen($prefix);
    if (\strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = \substr($class, $len);
    $file = $base_dir . \str_replace('\\', '/', $relative_class) . '.php';
    if (\file_exists($file)) {
        require_once $file;
    }
});
