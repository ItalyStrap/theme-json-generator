<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities;

/**
 * @psalm-api
 */
interface ColorModifierInterface
{
    public function tint(float $weight = 0): ColorInfoInterface;

    public function shade(float $weight = 0): ColorInfoInterface;

    public function tone(float $weight = 0): ColorInfoInterface;

    public function opacity(float $alpha = 1): ColorInfoInterface;

    public function darken(int $amount = 0): ColorInfoInterface;

    public function lighten(int $amount = 0): ColorInfoInterface;

    public function saturate(int $amount = 0): ColorInfoInterface;

    public function contrast(int $amount = 0): ColorInfoInterface;

    public function hueRotate(int $amount = 0): ColorInfoInterface;

    public function complementary(): ColorInfoInterface;

    public function color(): ColorInfoInterface;
}
