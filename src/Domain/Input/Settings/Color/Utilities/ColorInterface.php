<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities;

/**
 * @psalm-api
 */
// phpcs:ignore PHPCompatibility.Interfaces.NewInterfaces.stringableFound
interface ColorInterface extends \Stringable
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

    public function toHex(): ColorInterface;

    public function toHsl(): ColorInterface;

    public function toHsla(float $alpha = 1): ColorInterface;

    public function toRgb(): ColorInterface;

    public function toRgba(float $alpha = 1): ColorInterface;

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
