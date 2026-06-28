<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Factories;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final class ColorFactory implements ColorFactoryInterface
{
    /**
     * @throws \Exception
     */
    public function fromColorInfo(CssColorInterface $colorValue): CssColorInterface
    {
        return new CssColor((string) $colorValue);
    }

    /**
     * @throws \Exception
     */
    public function fromColorString(string $color): CssColorInterface
    {
        return new CssColor($color);
    }

    /**
     * @throws \Exception
     */
    public function hsla(int $hue, float $saturation, float $lightness, float $alpha = 1): CssColorInterface
    {
        return new CssColor(sprintf('hsla(%d, %s%%, %s%%, %s)', $hue, $saturation, $lightness, $alpha));
    }

    /**
     * @throws \Exception
     */
    public function rgba(int $red, int $green, int $blue, float $alpha = 1): CssColorInterface
    {
        return new CssColor(sprintf('rgba(%d, %d, %d, %s)', $red, $green, $blue, $alpha));
    }
}
