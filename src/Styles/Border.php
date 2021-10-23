<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

final class Border implements ArrayableInterface {

	use ImmutableCollectionTrait, CollectionToArray;

	const COLOR = 'color';
	const RADIUS = 'radius';
	const STYLE = 'style';
	const WIDTH = 'width';

	public function borderValues(
		string $color,
		string $radius,
		string $style,
		string $with
	): array {
		return [
			'color' => $color,
			'radius' => $radius,
			'style' => $style,
			'width' => $with,
		];
	}

	public function color( string $value ): self {
		$this->setCollection( self::COLOR, $value );
		return $this;
	}

	public function radius( string $value ): self {
		$this->setCollection( self::RADIUS, $value );
		return $this;
	}

	public function style( string $value ): self {
		$this->setCollection( self::STYLE, $value );
		return $this;
	}

	public function width( string $value ): self {
		$this->setCollection( self::WIDTH, $value );
		return $this;
	}
}
