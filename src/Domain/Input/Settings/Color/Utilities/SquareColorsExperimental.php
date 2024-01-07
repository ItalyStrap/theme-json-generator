<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities;

/**
 * @psalm-api
 */
class SquareColorsExperimental implements ColorsGenerator
{
    private ColorModifierInterface $colorModifier;

    public function __construct(ColorModifierInterface $colorModifier)
    {
        $this->colorModifier = $colorModifier;
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
