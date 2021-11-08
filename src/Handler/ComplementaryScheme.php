<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Handler;

final class ComplementaryScheme {

	public function __construct( ColorValue $color ) {
		$this->color = $color;
	}

	public function generate(): iterable {
		$hsl = $this->color->toHsl();

		$hue = $hsl->hue() > 180
			? $hsl->hue() - 180
			: $hsl->hue() + 180;

		yield new ColorValue(
			\sprintf(
				'hsl(%s, %s%%, %s%%)',
				$hue,
				$hsl->saturation(),
				$hsl->lightness()
			)
		);
	}
}
