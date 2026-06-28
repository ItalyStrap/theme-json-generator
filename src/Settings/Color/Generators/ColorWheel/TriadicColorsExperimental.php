<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorsGenerator;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Transformers\ColorModifierInterface;

final readonly class TriadicColorsExperimental implements ColorsGenerator
{
    public function __construct(
        private ColorModifierInterface $colorModifier,
        private int $angle = 120
    ) {
    }

    public function generate(): array
    {
        return (new ColorWheelRotation($this->colorModifier))->colorsRotatedBy([
            $this->angle,
            0,
            -$this->angle,
        ]);
    }
}
