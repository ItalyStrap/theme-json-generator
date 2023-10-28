<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Infrastructure\Commands;

use Composer\Plugin\Capability\CommandProvider;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Command;

class Provider implements CommandProvider
{
    public function getCommands(): array
    {
        return [
            new Command(),
        ];
    }
}
