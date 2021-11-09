<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Handler;

final class ColorFactory {

	/**
	 * @throws \Exception
	 */
	public function fromColorValue( ColorValue $colorValue ): ColorValue {
		return new ColorValue( (string) $colorValue->toHex() );
	}

	/**
	 * @throws \Exception
	 */
	public function fromColorString( string $color ): ColorValue {
		return new ColorValue( $color );
	}
}
