<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Collection;

trait Collectible
{
	/**
	 * @var CollectionInterface[]
	 */
	private $collection_of_collections = [];

	public function withCollection( CollectionInterface ...$collections ): void {
		$this->collection_of_collections = \array_merge_recursive(
			$this->collection_of_collections,
			$collections
		);
	}

	/**
	 * @param string $slug_or_default
	 * @return string
	 */
	private function findCssVariable( string $slug_or_default ): string {

		/** @var array $slugs_or_default */
		$slugs_or_default = (array) \explode( '|', $slug_or_default, 2 );

		$css_variable = $slugs_or_default[ 1 ] ?? '';

		try {
			$css_variable = $this->varOf( $slugs_or_default[ 0 ] );
		} catch ( \RuntimeException $exception ) {
			// fail in silence
		}

		if ( false !== \strpos( $slugs_or_default[0], '.' ) ) {
			$search_in_collection = \explode('.', $slugs_or_default[0] );
			foreach ( $this->collection_of_collections as $collection ) {
				if ( $collection->category() === $search_in_collection[ 0 ] ) {
					$css_variable = $collection->varOf( $search_in_collection[ 1 ] );
				}
			}
		}

		return $css_variable;
	}
}
