<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use Composer\Composer;
use Composer\Package\PackageInterface;
use Composer\Script\Event;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Exception;
use function array_replace_recursive;
use function dirname;
use function is_callable;

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
	public function createThemeJson( Composer $composer, IOInterface $io ): void {
		$rootPackage = $composer->getPackage();
		/** @var string $vendorPath */
		$vendorPath = $composer->getConfig()->get('vendor-dir');
		$rootPackagePath = dirname( $vendorPath );

		if ( $rootPackage->getType() === self::TYPE_THEME ) {
			$this->writeFile( $rootPackage, $rootPackagePath, $io );
			return;
		}

		$repo = $composer->getRepositoryManager();
		foreach ( $rootPackage->getRequires() as $link ) {
			$constraint = $link->getConstraint();
			$package = $repo->findPackage( $link->getTarget(), $constraint );
			$packagePath = $vendorPath . '/' . $link->getTarget();
			if ($package && $package->getType() === self::TYPE_THEME ) {
				$this->writeFile( $package, $packagePath, $io );
			}
		}
	}

	/**
	 * @param PackageInterface $package
	 * @param string $path
	 * @param IOInterface $io
	 */
	private function writeFile( PackageInterface $package, string $path, IOInterface $io ): void {
		$composer_extra = array_replace_recursive( $this->getDefaultExtra(), $package->getExtra() );

		/** @var array<string, mixed> $theme_json_config */
		$theme_json_config = $composer_extra[ 'theme-json' ];

		if ( ! is_callable( $theme_json_config[ 'callable' ] ) ) {
			throw new \RuntimeException( \sprintf(
				'Maybe the %s is not a valid callable',
				$theme_json_config[ 'callable' ]
			) );
		}

//		$path .= '/theme.json';

		try {
			( new JsonFileBuilder( $path . '/theme.json' ) )
				->build( $theme_json_config[ 'callable' ] );

			$path_for_theme_sass = $path . '/' . $theme_json_config[ 'path-for-theme-sass' ];
			if ( \is_writable( $path_for_theme_sass ) ) {
				( new ScssFileBuilder(
					\rtrim( $path_for_theme_sass, '/' ) . '/theme.scss'
				) )->build( $theme_json_config[ 'callable' ] );
			}

		} catch ( Exception $e ) {
			$io->write( $e->getMessage() );
		}
	}

	/**
	 * @return array<string, mixed>
	 */
	private function getDefaultExtra(): array {
		return [
			'theme-json' => [
				'callable' => false,
				'path-for-theme-sass'	=> '',
			],
		];
	}
}
