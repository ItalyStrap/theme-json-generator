<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorsGenerator;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final readonly class TetradicColors implements ColorsGenerator
{
    public function __construct(
        private CssColorInterface $color,
        private int $angle = 90
    ) {
    }

    public function generate(): array
    {
        return (new ColorWheelRotation($this->color))->colorsRotatedBy([
            $this->angle,
            $this->angle * 2,
            $this->angle * 3,
        ]);
    }
}
