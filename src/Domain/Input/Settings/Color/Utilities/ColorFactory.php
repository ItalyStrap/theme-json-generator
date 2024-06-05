<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities;

/**
 * @psalm-api
 */
final class ColorFactory implements ColorFactoryInterface
{
    /**
     * @throws \Exception
     */
    public function fromColorInfo(ColorInterface $colorValue): ColorInterface
    {
        return new Color((string) $colorValue);
    }

    /**
     * @throws \Exception
     */
    public function fromColorString(string $color): ColorInterface
    {
        return new Color($color);
    }

    /**
     * @throws \Exception
     */
    public function hsla(int $hue, float $saturation, float $lightness, float $alpha = 1): ColorInterface
    {
        return new Color("hsla($hue, $saturation%, $lightness%, $alpha)");
    }

    /**
     * @throws \Exception
     */
    public function rgba(int $red, int $green, int $blue, float $alpha = 1): ColorInterface
    {
        return new Color("rgba($red, $green, $blue, $alpha)");
    }
}
