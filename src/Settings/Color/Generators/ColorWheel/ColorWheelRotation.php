<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Transformers\ColorModifierInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final readonly class ColorWheelRotation
{
    public function __construct(private ColorModifierInterface $colorModifier)
    {
    }

    /**
     * Degrees are relative hue rotations. Values outside 0..360 are accepted
     * and normalized by the color modifier.
     *
     * @param int[] $degrees
     * @return CssColorInterface[]
     */
    public function colorsRotatedBy(array $degrees): array
    {
        $colors = [];
        foreach ($degrees as $degree) {
            $colors[] = $degree === 0
                ? $this->colorModifier->color()
                : $this->colorModifier->hueRotate($degree);
        }

        return $colors;
    }
}
