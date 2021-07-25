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
//			'post-autoload-dump'	=> 'createThemeJson',
			'pre-update-cmd'	=> 'createThemeJson',
//			'command'	=> 'createThemeJson',
		];
	}

	/**
	 * @param Event $event
	 */
	public static function run( Event $event ): void {
		$io = $event->getIO();
		$composer = $event->getComposer();

		$instance = new self();
		$instance->activate( $composer, $io );
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

		$this->io->write('CIAO');

		$this->composer->getConfig()->all();

		$package = $this->composer->getPackage();

		$this->io->write($package->getType());

		// wordpress-theme

		foreach ($package->getExtra() as $key => $item) {
			$this->io->write($key);
			$this->io->write($item);
		}

		$this->createThemeJson();
	}

	/**
	 * @inheritDoc
	 */
	public function deactivate( Composer $composer, IOInterface $io ): void {
	}

	public function createThemeJson(): void {
		$theme_json = 'tests/_data/fixtures/theme.json';

		$json_file = new ComposerFileJsonAdapter( new JsonFile( $theme_json ) );
		$json_file->write( ['ciao'=>'bello'] );
	}
}
