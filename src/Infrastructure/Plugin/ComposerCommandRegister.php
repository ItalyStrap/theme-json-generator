<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Infrastructure\Plugin;

use Composer\Composer as BaseComposer;
use Composer\Plugin\Capable;
use Composer\Script\Event;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use ItalyStrap\ThemeJsonGenerator\Application\Services\ThemeJsonGenerator;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Commands\Provider;

final class ComposerCommandRegister implements PluginInterface, Capable
{
    public static function run(Event $event): void
    {
        $io = $event->getIO();
        $composer = $event->getComposer();

        var_dump($event->getArguments());
        var_dump($event->getFlags());

        (new ThemeJsonGenerator())($composer, $io);
    }

    public function getCapabilities(): array
    {
        return [
            'Composer\Plugin\Capability\CommandProvider' => Provider::class,
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
