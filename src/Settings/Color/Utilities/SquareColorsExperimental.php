<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities;

final readonly class SquareColorsExperimental implements ColorsGenerator
{
    public function __construct(
        private ColorModifierInterface $colorModifier
    ) {
    }

    public function generate(): array
    {
        return [
            $this->colorModifier->color(),
            $this->colorModifier->hueRotate(90),
            $this->colorModifier->hueRotate(180),
            $this->colorModifier->hueRotate(270),
        ];
    }
}
