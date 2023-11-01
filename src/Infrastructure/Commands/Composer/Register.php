<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Infrastructure\Commands\Composer;

use Composer\Composer as BaseComposer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capability\CommandProvider;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use ItalyStrap\Config\Config;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer\ThemeJson;

final class Register implements PluginInterface, Capable, CommandProvider
{
    public function getCapabilities(): array
    {
        return [
            CommandProvider::class => self::class,
        ];
    }

    public function getCommands(): array
    {
        return [
            new ThemeJson(new Config()),
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
