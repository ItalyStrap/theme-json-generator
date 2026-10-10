<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorsGenerator;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final readonly class SplitComplementaryColors implements ColorsGenerator
{
    public function __construct(
        private CssColorInterface $color,
        private int $angle = 150
    ) {
    }

    public function generate(): array
    {
        return (new ColorWheelRotation($this->color))->colorsRotatedBy([
            $this->angle,
            -$this->angle,
        ]);
    }
}
