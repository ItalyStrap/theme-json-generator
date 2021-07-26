<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use Composer\Composer;
use Composer\Json\JsonFile;
use Composer\Script\Event;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

final class ComposerPlugin implements PluginInterface, EventSubscriberInterface {

	/**
	 * @psalm-suppress MissingConstructor
	 * @var Composer    $composer
	 *
	 * @psalm-suppress MissingConstructor
	 */
	private $composer;

	/**
	 * @psalm-suppress MissingConstructor
	 * @var IOInterface $io
	 */
	private $io;

	/**
	 * @inheritDoc
	 */
	public static function getSubscribedEvents(): array { // @phpstan-ignore-line
		return [
			'post-autoload-dump'	=> 'run',
			'post-install-cmd'	=> 'run',
			'post-update-cmd'	=> 'run',
		];
	}

	/**
	 * @param Event $event
	 */
	public static function run( Event $event ): void {
		$io = $event->getIO();
		$composer = $event->getComposer();

		$instance = new self();
		$instance->createThemeJson( $composer, $io );
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
		$this->composer = $composer;
		$this->io = $io;
	}

	/**
	 * @inheritDoc
	 */
	public function deactivate( Composer $composer, IOInterface $io ): void {
	}

	/**
	 * @param Composer $composer
	 * @param IOInterface $io
	 */
	public function createThemeJson( Composer $composer, IOInterface $io ) {
		$rootPackage = $composer->getPackage();

		if ( $rootPackage->getType() !== 'wordpress-theme' ) {
			return;
		}

		$default_extra = [
			'theme-json'	=> [
				'callable'	=> false,
			],
		];

		$composer_extra = \array_replace_recursive( $default_extra, $rootPackage->getExtra() );

		$theme_json_config = $composer_extra['theme-json'];

		$io->write('BEFORE CALLABLE CHECK');

		if ( ! \is_callable( $theme_json_config['callable'] ) ) {
			return;
		}

		$callable = $theme_json_config['callable'];

		$vendorPath = $composer->getConfig()->get('vendor-dir');
		$theme_json = dirname( $vendorPath ) . '/theme.json';

//		"callable": "\\ItalyStrap\\ExperimentalTheme\\getJsonData"
//		$array = \ItalyStrap\ExperimentalTheme\getJsonData();
//		$array = \ItalyStrap\ExperimentalTheme\getJsonData();

		try {
			$json_file = new ComposerFileJsonAdapter( new JsonFile( $theme_json ) );
			$json_file->write( $callable() );
		} catch ( \Exception $e ) {
			$io->write( $e->getMessage() );
		}
	}
}
