<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities;

use Spatie\Color\Color as SpatieColor;
use Spatie\Color\Factory as ColorFactory;
use Spatie\Color\Hsla;

/**
 * @psalm-api
 */
final class ColorInfo implements ColorInfoInterface
{
    private SpatieColor $spatieColor;

    private Hsla $hsla;

    /**
     * Luminance of #808080 or rgb(128,128,128) or hsl(0,0%,50%)
     */
    public const MEDIUM_LUMINANCE = 0.21951971807487;

    public function __construct(string $color)
    {
        $this->spatieColor = ColorFactory::fromString($color);
        $this->hsla = $this->spatieColor->toHsla();
    }

    public function isDark(): bool
    {
        return $this->luminance() <= self::MEDIUM_LUMINANCE;
    }

    public function isLight(): bool
    {
        return $this->luminance() > self::MEDIUM_LUMINANCE;
    }

    /**
     * Calculate the relative luminance of an RGB color.
     *
     * @author https://gist.github.com/sebdesign/a65cc39e3bcd81201609e6a8087a83b3
     *
     * @return float
     */
    public function luminance(): float
    {
        $rgb = $this->toRgb();
        return 0.2126 * ((int)$rgb->red() / 255) ** 2.2 +
            0.7152 * ((int)$rgb->green() / 255) ** 2.2 +
            0.0722 * ((int)$rgb->blue() / 255) ** 2.2;
    }

    /**
     * Calculate the relative luminance of two colors.
     *
     * @param self $color hex color
     *
     * @return float
     *
     * @throws \Exception
     */
    public function relativeLuminance(ColorInfoInterface $color): float
    {

        $colors = [
            $this->luminance(),
            $color->luminance(),
        ];

        \sort($colors);

        return ( $colors[1] + 0.05 ) / ( $colors[0] + 0.05 );
    }
    /**
     * @return string|int
     */
    public function red()
    {
        $red = $this->spatieColor->red();
        if (! \is_string($red) && ! \is_int($red)) {
            throw new \Exception('The color is not string or int');
        }

        return $red;
    }

    /**
     * @return string|int
     */
    public function green()
    {
        $green = $this->spatieColor->green();
        if (! \is_string($green) && ! \is_int($green)) {
            throw new \Exception('The color is not string or int');
        }

        return $green;
    }

    /**
     * @return string|int
     */
    public function blue()
    {
        $blue = $this->spatieColor->blue();
        if (! \is_string($blue) && ! \is_int($blue)) {
            throw new \Exception('The color is not string or int');
        }

        return $blue;
    }

    public function hue(): float
    {
        return $this->hsla->hue();
    }

    public function saturation(): float
    {
        return $this->hsla->saturation();
    }

    public function lightness(): float
    {
        return $this->hsla->lightness();
    }

    public function alpha(): float
    {
        return $this->hsla->alpha();
    }

    public function type(): string
    {
        return (new \ReflectionClass($this->spatieColor))->getShortName();
    }

    public function toHex(): self
    {
        return new self((string) $this->spatieColor->toHex());
    }

    public function toHsl(): self
    {
        return new self((string) $this->spatieColor->toHsl());
    }

    public function toHsla(float $alpha = 1): self
    {
        return new self((string) $this->spatieColor->toHsla($alpha));
    }

    public function toRgb(): self
    {
        return new self((string) $this->spatieColor->toRgb());
    }

    public function toRgba(float $alpha = 1): self
    {
        return new self((string) $this->spatieColor->toRgba($alpha));
    }

    public function __toString(): string
    {
        return (string) $this->spatieColor;
    }
}
