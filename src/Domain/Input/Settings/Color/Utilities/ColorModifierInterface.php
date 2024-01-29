<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities;

/**
 * @psalm-api
 */
interface ColorModifierInterface
{
    public function tint(float $weight = 0): ColorInterface;

    public function shade(float $weight = 0): ColorInterface;

    public function tone(float $weight = 0): ColorInterface;

    public function opacity(float $alpha = 1): ColorInterface;

    public function darken(int $amount = 0): ColorInterface;

    public function lighten(int $amount = 0): ColorInterface;

    public function saturate(int $amount = 0): ColorInterface;

    public function contrast(int $amount = 0): ColorInterface;

    public function hueRotate(int $amount = 0): ColorInterface;

    public function complementary(): ColorInterface;

    public function color(): ColorInterface;
}
