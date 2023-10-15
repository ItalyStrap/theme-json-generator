<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use Composer\Composer;
use Composer\Script\Event;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

/**
 * @deprecated
 */
final class ComposerPlugin implements PluginInterface
{
    public const TYPE_THEME = 'wordpress-theme';

    public static function run(Event $event): void
    {
        $io = $event->getIO();
        $composer = $event->getComposer();

        $instance = new \ItalyStrap\ThemeJsonGenerator\Composer\Plugin();
        $instance->createThemeJson($composer, $io);

        \trigger_error(
            \sprintf(
                'Deprecated %s called. Use %s instead',
                self::class,
                'ItalyStrap\\ThemeJsonGenerator\\Composer\\Plugin::run'
            ),
            E_USER_DEPRECATED
        );
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
    }

    public function activate(Composer $composer, IOInterface $io): void
    {
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
    }
}
