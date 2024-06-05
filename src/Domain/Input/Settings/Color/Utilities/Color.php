<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities;

use Spatie\Color\Color as SpatieColor;
use Spatie\Color\Factory as ColorFactory;
use Spatie\Color\Hsla;

/**
 * @psalm-api
 */
final class Color implements ColorInterface
{
    private SpatieColor $spatieColor;

    private string $type;

    private Hsla $hsla;

    /**
     * @var string|float
     */
    private $alpha = 1.0;

    /**
     * Luminance of #808080 or rgb(128,128,128) or hsl(0,0%,50%)
     * @var float
     */
    public const MEDIUM_LUMINANCE = 0.21951971807487;

    public function __construct(string $color)
    {
        $this->spatieColor = ColorFactory::fromString($color);

        $reflected = new \ReflectionObject($this->spatieColor);
        $this->type = $reflected->getShortName();
        if ($reflected->hasProperty('alpha')) {
            $reflectionProperty = $reflected->getProperty('alpha');
            $reflectionProperty->setAccessible(true);
            /**
             * @psalm-suppress MixedAssignment
             */
            $this->alpha = $reflectionProperty->getValue($this->spatieColor);
            $reflectionProperty->setAccessible(false);
        }

        $alpha = $this->fromHexToFloat($this->alpha);
        $this->hsla = $this->spatieColor->toHsla($alpha);
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
    public function relativeLuminance(ColorInterface $color): float
    {
        $colors = [
            $this->luminance(),
            $color->luminance(),
        ];

        \sort($colors);

        return ($colors[1] + 0.05) / ($colors[0] + 0.05);
    }

    /**
     * @return string|int
     */
    public function red()
    {
        $red = $this->spatieColor->red();
        if (!\is_string($red) && !\is_int($red)) {
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
        if (!\is_string($green) && !\is_int($green)) {
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
        if (!\is_string($blue) && !\is_int($blue)) {
            throw new \Exception('The color is not string or int');
        }

        return $blue;
    }

    public function hue(): int
    {
        return (int)\round($this->hsla->hue());
    }

    public function saturation(): int
    {
        return (int)\round($this->hsla->saturation());
    }

    public function lightness(): int
    {
        return (int)\round($this->hsla->lightness());
    }

    public function alpha()
    {
        return $this->alpha;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function toHex(): self
    {
        return new self((string) $this->spatieColor->toHex());
    }

    public function toHsl(): self
    {
        return new self((string) $this->spatieColor->toHsl());
    }

    public function toHsla(float $alpha = null): self
    {
        $alpha = $alpha ?? $this->fromHexToFloat($this->alpha);
        return new self((string) $this->spatieColor->toHsla($alpha));
    }

    public function toRgb(): self
    {
        return new self((string) $this->spatieColor->toRgb());
    }

    public function toRgba(float $alpha = null): self
    {
        $alpha = $alpha ?? $this->fromHexToFloat($this->alpha);
        return new self((string) $this->spatieColor->toRgba($alpha));
    }

    public function __toString(): string
    {
        return (string)$this->spatieColor;
    }

    /**
     * @param mixed $alpha
     */
    private function fromHexToFloat($alpha): float
    {
        return \is_string($alpha) ? \hexdec($alpha) / 255 : (float)$alpha;
    }
}
