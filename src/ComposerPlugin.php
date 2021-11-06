<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use Composer\Composer;
use Composer\Package\PackageInterface;
use Composer\Script\Event;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Exception;
use ItalyStrap\ThemeJsonGenerator\Files\JsonFileBuilder;
use ItalyStrap\ThemeJsonGenerator\Files\ScssFileBuilder;
use function array_replace_recursive;
use function dirname;
use function is_callable;

/**
 * @deprecated
 */
final class ComposerPlugin implements PluginInterface {

	const TYPE_THEME = 'wordpress-theme';

	/**
	 * @inheritDoc
	 * @return array<string, string>
	 */
//	public static function getSubscribedEvents(): array {
//		return [
//			'post-autoload-dump'	=> 'run',
//			'post-install-cmd'		=> 'run',
//			'post-update-cmd'		=> 'run',
//		];
//	}

	/**
	 * @param Event $event
	 */
	public static function run( Event $event ): void {
		$io = $event->getIO();
		$composer = $event->getComposer();

		$instance = new \ItalyStrap\ThemeJsonGenerator\Composer\Plugin();
		$instance->createThemeJson( $composer, $io );

		\trigger_error(
			\sprintf(
				'Deprecated %s called. Use %s instead',
				__CLASS__,
				'ItalyStrap\\ThemeJsonGenerator\\Composer\\Plugin::run'
			),
			E_USER_DEPRECATED
		);
	}

	/**
	 * @inheritDoc
	 */
	public function uninstall( Composer $composer, IOInterface $io ): void {
	}

	/**
	 * @inheritDoc
	 */
	public function activate( Composer $composer, IOInterface $io ): void {
	}

	/**
	 * @inheritDoc
	 */
	public function deactivate( Composer $composer, IOInterface $io ): void {
	}
}
