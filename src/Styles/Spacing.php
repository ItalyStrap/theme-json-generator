<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

use function array_replace;
use function implode;

final class Spacing implements ArrayableInterface, StylesInterface {

	use ImmutableCollectionTrait, CollectionToArray, UserDefinedPropertyTrait;

	const TOP = 'top';
	const RIGHT = 'right';
	const BOTTOM = 'bottom';
	const LEFT = 'left';

	public function top( string $value ): self {
		$this->setCollection( self::TOP, $value );
		return $this;
	}

	public function right( string $value ): self {
		$this->setCollection( self::RIGHT, $value );
		return $this;
	}

	public function bottom( string $value ): self {
		$this->setCollection( self::BOTTOM, $value );
		return $this;
	}

	public function left( string $value ): self {
		$this->setCollection( self::LEFT, $value );
		return $this;
	}

	public function __toString(): string {

		$default_collection = [
			self::TOP		=> '0',
			self::RIGHT		=> '0',
			self::BOTTOM	=> '0',
			self::LEFT		=> '0',
		];

		$collection = array_replace( $default_collection, $this->collection );

		if ( $this->assertCollectionHasAllFourValuesAreEqual( $collection ) ) {
			foreach ( $collection as $unit ) {
				return $unit;
			}
		}

		return implode( ' ', $collection );
	}

	/**
	 * @param array<string, string> $collection
	 * @return bool
	 */
	private function assertCollectionHasAllFourValuesAreEqual( array $collection ): bool {

		$filtered_collection = \array_map( function ( $value ) {
			return \preg_replace( '/\D/', '', $value );
		}, $collection );

		return \count( \array_unique( $filtered_collection ) ) === 1 && \count( $this->collection ) === 4;
	}
}
