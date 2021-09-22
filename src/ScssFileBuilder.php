<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use ItalyStrap\Config\Config;
use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\ThemeJsonGenerator\Helper\ConvertCase;

class ScssFileBuilder implements FileBuilder {

	use ConvertCase;

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var ConfigInterface<string, mixed>|null
	 */
	private $config;

	/**
	 * ThemeJsonGenerator constructor.
	 * @param string $path
	 * @param ConfigInterface<string, mixed>|null $config
	 */
	public function __construct( string $path, ConfigInterface $config = null ) {
		$this->path = $path;
		$this->config = $config ?? new Config();
	}

	/**
	 * @inheritDoc
	 */
	public function build( callable $callable ): void {
		$file = new \SplFileObject( $this->path, 'a' );

		if ( ! $file->isWritable() ) {
			throw new \RuntimeException('Ciccia culo');
		}

//		codecept_debug($callable());
//		codecept_debug($this->generateScssContent( $callable() ));

		$file->fwrite( $this->generateScssContent( $callable() ) );
		$file = null;
	}

	/**
	 * @param array<mixed, mixed> $data
	 * @return string
	 */
	private function generateScssContent( array $data ): string {
		if ( $data === [] ) {
			return '// No data are provided!';
		}

		$this->config->merge( $data ); /** @phpstan-ignore-line */

		$content = '';

		$schema = [
			'settings.color.palette'			=> '--wp--preset--color',
			'settings.color.gradients'			=> '--wp--preset--gradient',
			'settings.typography.fontFamilies'	=> '--wp--preset--font-family',
			'settings.typography.fontSizes'		=> '--wp--preset--font-size',
		];

		foreach ( $schema as $slug => $prefix ) {
			foreach ( (array) $this->config->get( $slug ) as $item ) { /** @phpstan-ignore-line */
				$content .= $this->generateScssVariableAndCssVariable( $item['slug'], $prefix );
			}
		}

		$custom = (array) $this->config->get( 'settings.custom' ); /** @phpstan-ignore-line */
		$custom = $this->flattenTree( $custom );

		foreach ( $custom as $property_name => $value ) {
			$content .= $this->generateScssVariableAndCssVariable( $property_name, '--wp-custom' );
		}

		return $content;
	}

	/**
	 * @param string $slug
	 * @return string
	 */
	private function generateScssVariableAndCssVariable( string $slug, string $prefix ): string {
		return \sprintf(
			'$%1$s: %2$s--%1$s !default;' . PHP_EOL,
			$this->camelToUnderscore( $slug ),
			$prefix
		);
	}

	/**
	 * @param array<string, string> $tree
	 * @param string $prefix
	 * @param string $token
	 * @return array<string, string>
	 *@author \WP_Theme_Json::flatten_tree
	 */
	private function flattenTree( array $tree, string $prefix = '', string $token = '--' ): array {
		$result = [];

		/**
		 * @var string $property
		 * @var string|array<string, string> $value
		 */
		foreach ( $tree as $property => $value ) {
			if ( ! \is_string( $property ) ) {
				throw new \RuntimeException(
					\sprintf(
						'Property key is not a string, actual value is: %s',
						(string) $property
					)
				);
			}

			$new_key = $prefix . \str_replace(
				'/',
				'-',
				$this->camelToUnderscore( $property )
			);

			if ( \is_array( $value ) ) {
				$new_prefix = $new_key . $token;
				$result     = \array_merge(
					$result,
					$this->flattenTree( $value, $new_prefix, $token )
				);
				continue;
			}

			$result[ $new_key ] = $value;
		}

		return $result;
	}
}
