<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Values;

interface CssColorInterface extends \Stringable
{
    /**
     * Compare normalized RGB channels and alpha without display rounding or tolerance.
     */
    public function equals(string|CssColorInterface $color): bool;

    public function isDark(): bool;

    public function isLight(): bool;

    /**
     * Calculate the relative luminance of an RGB color.
     *
     * @author https://gist.github.com/sebdesign/a65cc39e3bcd81201609e6a8087a83b3
     *
     * @return float
     */
    public function luminance(): float;

    public function toHex(): CssColorInterface;

    public function toHsl(): CssColorInterface;

    public function toHsla(float $alpha = 1): CssColorInterface;

    public function toRgb(): CssColorInterface;

    public function toRgba(float $alpha = 1): CssColorInterface;

    /**
     * @return string|int
     */
    public function red();

    /**
     * @return string|int
     */
    public function green();

    /**
     * @return string|int
     */
    public function blue();

    public function hue(): int;

    public function saturation(): int;

    public function lightness(): int;

    /**
     * @return string|float
     */
    public function alpha();

    public function type(): string;
}
