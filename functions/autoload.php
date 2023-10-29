<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use ItalyStrap\ThemeJsonGenerator\Application\Commands\WPCLI\ThemeJson;
use WP_CLI;

if (!class_exists('WP_CLI')) {
    return;
}

WP_CLI::add_command('theme-json generate', ThemeJson::class);
