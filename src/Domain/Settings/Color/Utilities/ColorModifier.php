<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities;

use Exception;

/**
 * @psalm-api
 */
final class ColorModifier implements ColorModifierInterface
{
    private ColorInfoInterface $color;

    private ColorFactoryInterface $color_factory;

    /**
     * @throws Exception
     */
    public function __construct(ColorInfoInterface $color, ColorFactoryInterface $factory = null)
    {
        $this->color = $color->toHsla();
        $this->color_factory = $factory ?? new ColorFactory();
    }

	public function color(): ColorInfoInterface
	{
		return $this->color;
	}

    public function tint(float $weight = 0): ColorInfoInterface
    {
        return $this->mixWith('rgb(255,255,255)', $weight);
    }

    public function shade(float $weight = 0): ColorInfoInterface
    {
        return $this->mixWith('rgb(0,0,0)', $weight);
    }

    public function tone(float $weight = 0): ColorInfoInterface
    {
        return $this->mixWith('rgb(128,128,128)', $weight);
    }

    public function opacity(float $alpha = 1): ColorInfoInterface
    {
        return $this->createNewColorWithChangedLightnessOrOpacity(0, $alpha);
    }

    public function darken(int $amount = 0): ColorInfoInterface
    {
        return $this->createNewColorWithChangedLightnessOrOpacity(- $amount);
    }

    public function lighten(int $amount = 0): ColorInfoInterface
    {
        return $this->createNewColorWithChangedLightnessOrOpacity($amount);
    }

    public function saturate(int $amount = 0): ColorInfoInterface
    {
        return $this->createNewColorWithChangedSaturation($amount);
    }

    public function contrast(int $amount = 0): ColorInfoInterface
    {
        return $this->createNewColorWithChangedContrast($amount);
    }

    public function complementary(): ColorInfoInterface
    {
        $rotation = $this->color->hue() > 180 ? -180 : 180;
        return $this->hueRotate($rotation);
    }

    public function invert(): ColorInfoInterface
    {
		return $this->createNewColorFrom(
			(string) $this->color->hue(),
			(string) $this->color->saturation(),
			(string) $this->sanitizeFromFloatToInteger(100 - $this->color->lightness()),
			(string) $this->color->alpha()
		);
    }

	public function hueRotate(int $amount = 0): ColorInfoInterface
	{
		$sumHue = $this->color->hue() + $amount;

		if ($sumHue < 0) {
			$sumHue = 360 + $sumHue;
		}

		return $this->color_factory->fromColorString(
			\sprintf(
				'hsla(%s, %s%%, %s%%, %s)',
				$sumHue,
				$this->color->saturation(),
				$this->color->lightness(),
				$this->color->alpha()
			)
		);
	}

	private function mixWith(string $color_string, float $weight = 0): ColorInfoInterface
    {

        $result = $this->mixRgb(
            $this->color_factory->fromColorString($color_string)->toRgb(),
            $this->color->toRgb(),
            $weight > 1 ? $weight / 100 : $weight
        );

        return $this->color_factory->fromColorString(\sprintf(
            'rgb(%s)',
            \implode(',', $result)
        ));
    }

	private function mixRgb(ColorInfoInterface $color_1, ColorInfoInterface $color_2, float $weight = 0.5): array
    {
        $f = fn(int $x): float => $weight * $x;
        $g = fn(int $x): float => ( 1 - $weight ) * $x;
        $h = fn(float $x, float $y): float => \round($x + $y);

        return \array_map(
            $h,
            \array_map($f, [ $color_1->red(), $color_1->green(),$color_1->blue() ]),
            \array_map($g, [ $color_2->red(), $color_2->green(), $color_2->blue() ])
        );
    }

    private function createNewColorWithChangedLightnessOrOpacity(int $amount, float $alpha = 1): ColorInfoInterface
    {
		return $this->createNewColorFrom(
			(string) $this->color->hue(),
			(string) $this->color->saturation(),
			(string) $this->sanitizeFromFloatToInteger($this->color->lightness() + $amount),
			(string) $alpha
		);
    }

    private function createNewColorWithChangedSaturation(int $amount): ColorInfoInterface
    {
		return $this->createNewColorFrom(
			(string) $this->color->hue(),
			(string) $this->sanitizeFromFloatToInteger($this->color->saturation() + $amount),
			(string) $this->color->lightness(),
			(string) $this->color->alpha()
		);
    }

    private function createNewColorWithChangedContrast(int $amount): ColorInfoInterface
    {
		return $this->createNewColorFrom(
			(string) $this->color->hue(),
			(string) $this->sanitizeFromFloatToInteger($this->color->saturation() + $amount),
			(string) $this->sanitizeFromFloatToInteger($this->color->lightness() + $amount),
			(string) $this->color->alpha()
		);
    }

	private function createNewColorFrom(string $hue, string $saturation, string $lightness, string $alpha): ColorInfoInterface
	{
		return $this->color_factory->fromColorString(
			\sprintf(
				'hsla(%s, %s%%, %s%%, %s)',
				$hue,
				$saturation,
				$lightness,
				$alpha
			)
		);
	}

	private function sanitizeFromFloatToInteger(float $value): int
	{
		return $value > 100
			? 100
			: ( $value < 0 ? 0 : (int) $value );
	}
}
