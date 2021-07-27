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

	const TYPE_THEME = 'wordpress-theme';

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
			'post-install-cmd'		=> 'run',
			'post-update-cmd'		=> 'run',
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
		$vendorPath = $composer->getConfig()->get('vendor-dir');
		$rootPackagePath = \dirname( $vendorPath );

		if ( $rootPackage->getType() === self::TYPE_THEME ) {
			$this->writeFile( $rootPackage, $rootPackagePath, $io );
			return;
		}

		$repo = $composer->getRepositoryManager();
		/** @var \Composer\Package\Link $link */
		foreach ( $rootPackage->getRequires() as $link ) {
			$constraint = $link->getConstraint();
			$package = $repo->findPackage($link->getTarget(), $constraint);
			$packagePath = $vendorPath . '/' . $link->getTarget();
			if ($package && $package->getType() === self::TYPE_THEME ) {
				$this->writeFile( $package, $packagePath, $io );
			}
		}
	}

	/**
	 * @param \Composer\Package\PackageInterface $package
	 * @param string $path
	 * @param IOInterface $io
	 */
	private function writeFile( \Composer\Package\PackageInterface $package, string $path, IOInterface $io ): void {
		$composer_extra = \array_replace_recursive( $this->getDefaultExtra(), $package->getExtra() );

		$theme_json_config = $composer_extra[ 'theme-json' ];

		if ( ! \is_callable( $theme_json_config[ 'callable' ] ) ) {
			return;
		}

		$callable = $theme_json_config[ 'callable' ];

		$path = $path . '/theme.json';

		try {
			$json_file = new ComposerFileJsonAdapter( new JsonFile( $path ) );
			$json_file->write( $callable() );
		} catch ( \Exception $e ) {
			$io->write( $e->getMessage() );
		}
	}

	/**
	 * @return \false[][]
	 */
	private function getDefaultExtra(): array {
		return [
			'theme-json' => [
				'callable' => false,
			],
		];
	}
}
