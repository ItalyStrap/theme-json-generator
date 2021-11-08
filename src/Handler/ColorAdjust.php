<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Handler;

use Exception;

final class ColorAdjust {

	private ColorValue $color;
	private ColorsBlender $blender;

	/**
	 * @throws Exception
	 */
	public function __construct( ColorValue $color, ColorsBlender $blender ) {
		$this->color = $color;
		$this->blender = $blender;
	}

	public function tint( float $weight = 0 ): ColorValue {
		return $this->blender->mixBy( 'rgb(255,255,255)', $weight );
	}

	public function shade( float $weight = 0 ): ColorValue {
		return $this->blender->mixBy( 'rgb(0,0,0)', $weight );
	}

	public function tone( float $weight = 0 ): ColorValue {
		return $this->blender->mixBy( 'rgb(128,128,128)', $weight );
	}

	public function opacity( float $alpha = 1 ): ColorValue {
		return $this->createNewColorWithChangedLightnessOrOpacity( 0, $alpha );
	}

	public function darken( int $amount = 0 ): ColorValue {
		return $this->createNewColorWithChangedLightnessOrOpacity( - $amount );
	}

	public function lighten( int $amount = 0 ): ColorValue {
		return $this->createNewColorWithChangedLightnessOrOpacity( $amount );
	}

	/**
	 * @param int $amount
	 * @param float $alpha
	 * @return ColorValue
	 * @throws Exception
	 */
	private function createNewColorWithChangedLightnessOrOpacity( int $amount, float $alpha = 1 ): ColorValue {
		$hsla = $this->color->toHsla();
		return new ColorValue(
			\sprintf(
				'hsla(%s, %s%%, %s%%, %s)',
				$hsla->hue(),
				$hsla->saturation(),
				$this->sanitizeLightness( $hsla, $amount ),
				$hsla->alpha()
			)
		);
	}

	/**
	 * @param ColorValue $hsl
	 * @param int $amount
	 * @return float|int
	 */
	private function sanitizeLightness( ColorValue $hsl, int $amount ) {
		$lightness = $hsl->lightness() + $amount;
		return $lightness > 100 ? 100 : ( $lightness < 0 ? 0 : $lightness );
	}
}
