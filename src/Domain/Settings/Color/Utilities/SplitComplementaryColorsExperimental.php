<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities;

use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities\ColorsGenerator;

/**
 * @psalm-api
 */
class SplitComplementaryColorsExperimental implements ColorsGenerator
{
    private ColorModifierInterface $colorModifier;

    public function __construct(ColorModifierInterface $colorModifier)
    {
        $this->colorModifier = $colorModifier;
    }

    public function generate(): array
    {
        return [
            $this->colorModifier->hueRotate(150),
            $this->colorModifier->color(),
            $this->colorModifier->hueRotate(-150),
        ];
    }
}
