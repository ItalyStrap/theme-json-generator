<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

class ScssFileBuilder implements FileBuilder {


	/**
	 * @var string
	 */
	private $path;

	/**
	 * ThemeJsonGenerator constructor.
	 * @param string $path
	 */
	public function __construct( string $path ) {
		$this->path = $path;
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
		$content = '';
		$settings = $data['settings'];

		$schema = [
			'settings.color.palette'			=> '--wp--preset--color',
			'settings.color.gradients'			=> '--wp--preset--gradient',
			'settings.typography.fontFamilies'	=> '--wp--preset--font-family',
			'settings.typography.fontSizes'		=> '--wp--preset--font-size',
			'settings.custom'					=> '--wp-custom',
		];

		// --wp--preset--color--<slug>
		$items = $settings['color']['palette'] ?? [];
		foreach ( $items as $item ) {
			$content .= $this->generateScssVariableAndCssVariable( $item['slug'], '--wp--preset--color' );
		}

		// --wp--preset--gradient--<slug>
		$items = $settings['color']['gradients'] ?? [];
		foreach ( $items as $item ) {
			$content .= $this->generateScssVariableAndCssVariable( $item['slug'], '--wp--preset--gradient' );
		}

//		--wp--preset--font-family--<slug>
		$items = $settings['typography']['fontFamilies'] ?? [];
		foreach ( $items as $item ) {
			$content .= $this->generateScssVariableAndCssVariable( $item['slug'], '--wp--preset--font-family' );
		}

//		--wp--preset--font-size--<slug>
		$items = $settings['typography']['fontSizes'] ?? [];
		foreach ( $items as $item ) {
			$content .= $this->generateScssVariableAndCssVariable( $item['slug'], '--wp--preset--font-size' );
		}

		// --wp--custom--<slug>
		$custom = $settings['custom'] ?? [];
		foreach ( $custom as $property_name => $value ) {
//			codecept_debug( $this->flattenTree( $custom, '--wp-custom--' ) );
//			$content .= $this->flattenTree( $custom, '--wp-custom' );
			$content .= $this->generateScssVariableAndCssVariable( $property_name, '--wp-custom' );
//			break;
		}

		return $content;
	}

	/**
	 * @param string $slug
	 * @return string
	 */
	private function generateScssVariableAndCssVariable( string $slug, string $prefix ): string {
		return \sprintf(
			'$%1$s: %2$s--%1$s;' . PHP_EOL,
			$slug,
			$prefix
		);
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
