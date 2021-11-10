<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

trait UserDefinedPropertyTrait {

	public function property( string $property, string $value ): self {
		$this->setCollection( $property, $value );
		return $this;
	}
}
