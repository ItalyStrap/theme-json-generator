<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Infrastructure\Plugin;

use Composer\Composer as BaseComposer;
use Composer\Plugin\Capability\CommandProvider;
use Composer\Plugin\Capable;
use Composer\Script\Event;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Commands\Provider;

final class ComposerCommandRegister implements PluginInterface, Capable
{
    public function getCapabilities(): array
    {
        return [
            CommandProvider::class => Provider::class,
        ];
    }

    public function uninstall(BaseComposer $composer, IOInterface $io): void
    {
    }

    public function activate(BaseComposer $composer, IOInterface $io): void
    {
    }

    public function deactivate(BaseComposer $composer, IOInterface $io): void
    {
    }
}
