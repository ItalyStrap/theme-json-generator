<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities;

readonly class SquareColorsExperimental implements ColorsGenerator
{
    public function __construct(
        private ColorModifierInterface $colorModifier
    ) {
    }

    public function generate(): array
    {
        return [
            $this->colorModifier->color(),
            $this->colorModifier->hueRotate(60),
            $this->colorModifier->hueRotate(120),
            $this->colorModifier->hueRotate(180),
        ];
    }
}
