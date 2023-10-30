<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Handler;

/**
 * @psalm-api
 */
interface ColorInfoInterface
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
     * @return mixed
     */
    public function red();

    /**
     * @return mixed
     */
    public function green();

    /**
     * @return mixed
     */
    public function blue();

    public function hue(): float;

    public function saturation(): float;

    public function lightness(): float;

    public function alpha(): float;

    public function type(): string;
}
