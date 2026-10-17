<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Transformers;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

interface ColorModifierInterface
{
    public function tint(float $weight = 0): CssColorInterface;

    public function shade(float $weight = 0): CssColorInterface;

    public function tone(float $weight = 0): CssColorInterface;

    public function opacity(?float $alpha = null): CssColorInterface;

    public function darken(float $amount = 0): CssColorInterface;

    public function lighten(float $amount = 0): CssColorInterface;

    public function saturate(float $amount = 0): CssColorInterface;

    public function contrast(float $amount = 0): CssColorInterface;

    public function hueRotate(int $amount = 0): CssColorInterface;

    public function complementary(): CssColorInterface;

    public function color(): CssColorInterface;
}
