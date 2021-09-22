<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Collection;

use ItalyStrap\ThemeJsonGenerator\Helper\ConvertCase;

final class Preset implements CollectionInterface {

	use Collectible, ConvertCase;

	/**
	 * @var array[]
	 */
	private $collection;

	/**
	 * @var string
	 */
	private $category;

	/**
	 * @var string
	 */
	private $key;

	/**
	 * @param array[] $collection
	 * @param string $category
	 * @param string $key
	 */
	public function __construct( array $collection, string $category, string $key = '' ) {
		$this->collection = $collection;
		$this->category = $category;
		$this->key = '' === $key ? $category : $key ;
	}

	/**
	 * @inerhitDoc
	 */
	public function category(): string {
		return $this->category;
	}

	/**
	 * @inerhitDoc
	 */
	public function propOf( string $slug ): string {

		foreach ( $this->collection as $item ) {
			if ( \in_array( $slug, $item, true ) ) {
				return \sprintf(
					'--wp--preset--%s--%s',
					$this->camelToUnderscore( $this->category() ),
					$this->camelToUnderscore( $slug )
				);
			}
		}

		throw new \RuntimeException("{$slug} does not exists." );
	}

	/**
	 * @inerhitDoc
	 */
	public function varOf( string $slug ): string {
		return \sprintf(
			'var(%s)',
			$this->propOf( $slug )
		);
	}

	/**
	 * @inerhitDoc
	 */
	public function value( string $slug ): string {

		/**
		 * @var $item array
		 */
		foreach ( $this->toArray() as $item ) {
			if ( \in_array( $slug, $item, true ) ) {
				return $item[ $this->key ];
			}
		}

		throw new \RuntimeException("Value of {$slug} does not exists." );
	}

	/**
	 * @inerhitDoc
	 */
	public function toArray(): array {

		foreach ( $this->collection as $key => $item ) {
			\preg_match_all(
				'/(?:{{.*?}})+/',
				$item[ $this->key ],
				$matches
			);

			foreach ( $matches[0] as $match ) {
				$this->collection[ $key ][ $this->key ] = \str_replace(
					$match,
					$this->findCssVariable( \str_replace( ['{{', '}}' ], '', $match ) ),
					$this->collection[ $key ][ $this->key ]
				);
			}
		}

		return $this->collection;
	}
}
