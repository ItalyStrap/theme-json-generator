<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Handler;

use Exception;

/**
 * @psalm-api
 */
final class ColorAdjust implements ColorAdjustInterface
{
    private ColorInfoInterface $color;

    private ColorFactoryInterface $color_factory;

    /**
     * @throws Exception
     */
    public function __construct(ColorInfoInterface $color, ColorFactoryInterface $factory = null)
    {
        $this->color = $color;
        $this->color_factory = $factory ?? new ColorFactory();
    }

    public function tint(float $weight = 0): ColorValue
    {
        return $this->mixWith('rgb(255,255,255)', $weight);
    }

    public function shade(float $weight = 0): ColorValue
    {
        return $this->mixWith('rgb(0,0,0)', $weight);
    }

    public function tone(float $weight = 0): ColorValue
    {
        return $this->mixWith('rgb(128,128,128)', $weight);
    }

    public function opacity(float $alpha = 1): ColorValue
    {
        return $this->createNewColorWithChangedLightnessOrOpacity(0, $alpha);
    }

    public function darken(int $amount = 0): ColorValue
    {
        return $this->createNewColorWithChangedLightnessOrOpacity(- $amount);
    }

    public function lighten(int $amount = 0): ColorValue
    {
        return $this->createNewColorWithChangedLightnessOrOpacity($amount);
    }

    private function mixWith(string $color_string, float $weight = 0): ColorValue
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
        $g = fn (int $x): float => ( 1 - $weight ) * $x;
        $h = fn (float $x, float $y): float => \round($x + $y);

        return \array_map(
            $h,
            \array_map($f, [ $color_1->red(), $color_1->green(),$color_1->blue() ]),
            \array_map($g, [ $color_2->red(), $color_2->green(), $color_2->blue() ])
        );
    }

    /**
     * @param int $amount
     * @param float $alpha
     * @return ColorValue
     * @throws Exception
     */
    private function createNewColorWithChangedLightnessOrOpacity(int $amount, float $alpha = 1): ColorValue
    {
        $hsla = $this->color->toHsla();
        return $this->color_factory->fromColorString(
            \sprintf(
                'hsla(%s, %s%%, %s%%, %s)',
                $hsla->hue(),
                $hsla->saturation(),
                $this->sanitizeLightness($hsla, $amount),
                $hsla->alpha()
            )
        );
    }

    /**
     * @param ColorInfoInterface $hsl
     * @param int $amount
     * @return float|int
     */
    private function sanitizeLightness(ColorInfoInterface $hsl, int $amount)
    {
        $lightness = $hsl->lightness() + $amount;
        return $lightness > 100 ? 100 : ( $lightness < 0 ? 0 : $lightness );
    }
}
