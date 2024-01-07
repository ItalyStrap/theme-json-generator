<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Infrastructure\Cli\WPCLI;

use ItalyStrap\ThemeJsonGenerator\Application\Commands\WPCLI\Dump;

/**
 * @psalm-api
 */
final class Bootstrap
{
    public function __invoke(): void
    {
        if (!\class_exists('WP_CLI')) {
            return;
        }

        \WP_CLI::add_command(Dump::NAME, Dump::class);
    }
}
