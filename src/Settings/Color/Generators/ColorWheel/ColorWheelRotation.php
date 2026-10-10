<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Transformers\ColorModifier;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final readonly class ColorWheelRotation
{
    public function __construct(private CssColorInterface $color)
    {
    }

    /**
     * The base color is always returned first. Degrees are relative hue rotations
     * added after the base color. Values outside 0..360 are accepted and
     * normalized by the color modifier.
     *
     * @param int[] $degrees
     * @return CssColorInterface[]
     */
    public function colorsRotatedBy(array $degrees): array
    {
        $colorModifier = new ColorModifier($this->color);
        $colors = [0 => $colorModifier->color()];
        foreach ($degrees as $degree) {
            if ($degree === 0) {
                continue;
            }

            $colors[] = $colorModifier->hueRotate($degree);
        }

        return $colors;
    }
}
