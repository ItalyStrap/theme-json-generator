<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorsGenerator;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Transformers\ColorModifierInterface;

final readonly class SquareColorsExperimental implements ColorsGenerator
{
    public function __construct(
        private ColorModifierInterface $colorModifier,
        private int $angle = 90
    ) {
    }

    public function generate(): array
    {
        return (new ColorWheelRotation($this->colorModifier))->colorsRotatedBy([
            0,
            $this->angle,
            $this->angle * 2,
            $this->angle * 3,
        ]);
    }
}
