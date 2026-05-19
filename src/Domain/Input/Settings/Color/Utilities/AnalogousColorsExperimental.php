<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities;

readonly class AnalogousColorsExperimental implements ColorsGenerator
{
    public function __construct(private ColorModifierInterface $colorModifier)
    {
    }

    public function generate(): array
    {
        return [
            $this->colorModifier->hueRotate(30),
            $this->colorModifier->color(),
            $this->colorModifier->hueRotate(-30),
        ];
    }
}
