<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use ItalyStrap\Config\Config;
use ItalyStrap\Config\ConfigInterface;

class ScssFileBuilder implements FileBuilder {

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var Config|ConfigInterface
	 */
	private $config;

	/**
	 * ThemeJsonGenerator constructor.
	 * @param string $path
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

	private function generateScssContent( array $data ): string {
		if ( $data === [] ) {
			return '// No data are provided!';
		}

		$this->config->merge( $data );

		$content = '';

		$schema = [
			'settings.color.palette'			=> '--wp--preset--color',
			'settings.color.gradients'			=> '--wp--preset--gradient',
			'settings.typography.fontFamilies'	=> '--wp--preset--font-family',
			'settings.typography.fontSizes'		=> '--wp--preset--font-size',
		];

		foreach ( $schema as $slug => $prefix ) {
			foreach ( (array) $this->config->get( $slug ) as $item ) {
				$content .= $this->generateScssVariableAndCssVariable( $item['slug'], $prefix );
			}
		}

		$custom = (array) $this->config->get( 'settings.custom' );
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
	 * @link https://stackoverflow.com/a/40514305/7486194
	 * @param string $string
	 * @param string $us
	 * @return string
	 */
	private function camelToUnderscore( string $string, string $us = '-' ): string {
		return \strtolower( \preg_replace(
			'/(?<=\d)(?=[A-Za-z])|(?<=[A-Za-z])(?=\d)|(?<=[a-z])(?=[A-Z])/',
			$us,
			$string
		) );
	}

	/**
	 * @author \WP_Theme_Json::flatten_tree
	 * @param array $tree
	 * @param string $prefix
	 * @param string $token
	 * @return array
	 */
	private function flattenTree( array $tree, $prefix = '', $token = '--' ): array {
		$result = [];
		foreach ( $tree as $property => $value ) {
			if ( ! \is_string( $property ) ) {
				throw new \RuntimeException(
					\sprintf(
						'Property key is not a string, actual value is: %s',
						(string) $property
					)
				);
			}

			$new_key = $prefix . str_replace(
				'/',
				'-',
				\strtolower( \preg_replace( '/(?<!^)[A-Z]/', '-$0', $property ) ) // CamelCase to kebab-case.
			);

			if ( is_array( $value ) ) {
				$new_prefix = $new_key . $token;
				$result     = array_merge(
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
