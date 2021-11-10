<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

final class Color implements ArrayableInterface, StylesInterface {

	use ImmutableCollectionTrait, CollectionToArray, UserDefinedPropertyTrait;

	const BACKGROUND = 'background';
	const GRADIENT = 'gradient';
	const TEXT = 'text';

	public function background( string $value ): self {
		$this->setCollection( self::BACKGROUND, $value );
		return $this;
	}

	public function gradient( string $value ): self {
		$this->setCollection( self::GRADIENT, $value );
		return $this;
	}

	public function text( string $value ): self {
		$this->setCollection( self::TEXT, $value );
		return $this;
	}
}
