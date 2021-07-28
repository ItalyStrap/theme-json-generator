<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\CLI;

use Exception;
use ItalyStrap\ThemeJsonGenerator\JsonFileBuilder;
use WP_CLI;
use function array_replace_recursive;
use function get_stylesheet_directory;
use function get_template_directory;
use function is_callable;
use function is_child_theme;
use function sprintf;
use function strval;

/**
 * @todo Add a system to generate the file for parent and child but not with the same config...
 * Class Command
 * @package ItalyStrap\ThemeJsonGenerator\CLI
 */
final class Command {

	public function __invoke( array $args, array $assoc_args ) {

		/**
		 * --parent
		 * : Argument to generate theme.json also for parent theme
		 * @var array
		 */
		$assoc_args_default = [
			'parent'	=> false,
		];

		$assoc_args = array_replace_recursive( $assoc_args_default, $assoc_args );

		$callable = strval( WP_CLI::get_runner()->extra_config['THEME_JSON_CALLABLE'] );

		if ( ! is_callable( $callable ) ) {
			WP_CLI::line( strval( $callable ) . " is not a valid callable" );
		}

		$theme_json_path[] = get_stylesheet_directory() . '/theme.json';

		if ( is_child_theme() && (bool) $assoc_args['parent'] ) {
			$theme_json_path[] = get_template_directory() . '/theme.json';
		}

		foreach ( $theme_json_path as $path ) {
			$this->loopsThemePathAndGenerateFile( $path, $callable );
		}
	}

	/**
	 * @param string $path
	 * @param callable $callable
	 */
	private function loopsThemePathAndGenerateFile( string $path, callable $callable ): void {
		try {
			(new JsonFileBuilder( $path ))->build( $callable );
			WP_CLI::success( sprintf(
				'%s was generated!',
				$path
			) );
		} catch (Exception $e) {
			WP_CLI::line( $e->getMessage() );
		}
	}
}
