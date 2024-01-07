<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities;

/**
 * @psalm-api
 */
interface ColorInfoInterface extends \Stringable
{
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

    public function toHex(): ColorInfoInterface;

    public function toHsl(): ColorInfoInterface;

    public function toHsla(float $alpha = 1): ColorInfoInterface;

    public function toRgb(): ColorInfoInterface;

    public function toRgba(float $alpha = 1): ColorInfoInterface;

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

    public function alpha(): float;

    public function type(): string;
}
