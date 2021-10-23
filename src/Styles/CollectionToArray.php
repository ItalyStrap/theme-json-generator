<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

trait CollectionToArray {

	public function toArray(): array {
		return \array_filter( $this->collection );
	}
}
