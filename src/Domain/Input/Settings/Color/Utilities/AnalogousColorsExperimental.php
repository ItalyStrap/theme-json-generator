<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities;

/**
 * @psalm-api
 */
class AnalogousColorsExperimental implements ColorsGenerator
{
    private ColorModifierInterface $colorModifier;

    public function __construct(ColorModifierInterface $colorModifier)
    {
        $this->colorModifier = $colorModifier;
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
