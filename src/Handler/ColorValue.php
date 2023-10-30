<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Handler;

use Spatie\Color\Color;
use Spatie\Color\Factory as ColorFactory;
use Spatie\Color\Hsla;

/**
 * @psalm-api
 */
final class ColorValue implements ColorInfoInterface
{
    private Color $s_color;

    private Hsla $hsla;

    /**
     * Luminance of #808080 or rgb(128,128,128)
     */
    public const MEDIUM_LUMINANCE = 0.21951971807487;

    /**
     * @throws \Exception
     */
    public static function fromColorValue(ColorValue $colorValue)
    {
        return new self((string) $colorValue->toHex());
    }

    /**
     * @throws \Exception
     */
    public function __construct(string $color)
    {
        $this->s_color = ColorFactory::fromString($color);
        $this->hsla = clone $this->s_color->toHsla();
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
        return 0.2126 * ($rgb->red() / 255) ** 2.2 +
            0.7152 * ($rgb->green() / 255) ** 2.2 +
            0.0722 * ($rgb->blue() / 255) ** 2.2;
    }

    public function toHex(): self
    {
        return new self((string) $this->s_color->toHex());
    }

    public function toHsl(): self
    {
        return new self((string) $this->s_color->toHsl());
    }

    public function toHsla(float $alpha = 1): self
    {
        return new self((string) $this->s_color->toHsla($alpha));
    }

    public function toRgb(): self
    {
        return new self((string) $this->s_color->toRgb());
    }

    public function toRgba(float $alpha = 1): self
    {
        return new self((string) $this->s_color->toRgba($alpha));
    }

    public function __toString(): string
    {
        return (string) $this->s_color;
    }

    /**
     * TO have a return array we need to return self object
     */
    public function toArray(): iterable
    {
//      yield $this->red();
//      yield $this->green();
//      yield $this->blue();

        return [
            $this->red(),
            $this->green(),
            $this->blue(),
        ];
    }

    /**
     * @return mixed
     */
    public function red()
    {
        return $this->s_color->red();
    }

    /**
     * @return mixed
     */
    public function green()
    {
        return $this->s_color->green();
    }

    /**
     * @return mixed
     */
    public function blue()
    {
        return $this->s_color->blue();
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
        return '';
    }
}
