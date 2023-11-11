<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Infrastructure\Commands\WPCLI;

use ItalyStrap\ThemeJsonGenerator\Application\Commands\WPCLI\ThemeJson;

/**
 * @psalm-api
 */
final class Register
{
    public function __invoke(): void
    {
        if (!\class_exists('WP_CLI')) {
            return;
        }

        \WP_CLI::add_command(ThemeJson::NAME, ThemeJson::class);
    }
}
