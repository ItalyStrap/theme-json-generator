<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

final class Typography implements ArrayableInterface, StylesInterface {

	use ImmutableCollectionTrait, CollectionToArray, UserDefinedPropertyTrait;

	const FONT_FAMILY		= 'fontFamily';
	const FONT_SIZE			= 'fontSize';
	const FONT_STYLE		= 'fontStyle';
	const FONT_WEIGHT		= 'fontWeight';
	const LETTER_SPACING	= 'letterSpacing';
	const LINE_HEIGHT		= 'lineHeight';
	const TEXT_DECORATION	= 'textDecoration';
	const TEXT_TRANSFORM	= 'textTransform';

	public function fontFamily( string $value ): self {
		$this->setCollection( self::FONT_FAMILY, $value );
		return $this;
	}

	public function fontSize( string $value ): self {
		$this->setCollection( self::FONT_SIZE, $value );
		return $this;
	}

	public function fontStyle( string $value ): self {
		$this->setCollection( self::FONT_STYLE, $value );
		return $this;
	}

	public function fontWeight( string $value ): self {
		$this->setCollection( self::FONT_WEIGHT, $value );
		return $this;
	}

	public function letterSpacing( string $value ): self {
		$this->setCollection( self::LETTER_SPACING, $value );
		return $this;
	}

	public function lineHeight( string $value ): self {
		$this->setCollection( self::LINE_HEIGHT, $value );
		return $this;
	}

	public function textDecoration( string $value ): self {
		$this->setCollection( self::TEXT_DECORATION, $value );
		return $this;
	}

	public function textTransform( string $value ): self {
		$this->setCollection( self::TEXT_TRANSFORM, $value );
		return $this;
	}
}
