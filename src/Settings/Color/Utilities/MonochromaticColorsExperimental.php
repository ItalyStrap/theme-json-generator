<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities;

final class MonochromaticColorsExperimental implements ColorsGenerator
{
    /**
     * @param array<array-key, int|float> $steps
     */
    public function __construct(
        private readonly ColorModifierInterface $colorModifier,
        private array $steps
    ) {
    }

    public function generate(): array
    {
        \arsort($this->steps);
        $colors = [];
        foreach ($this->steps as $weight) {
            $colors[] = $this->colorModifier->tint((float)$weight);
        }

        $colors[] = $this->colorModifier->tint();

        \asort($this->steps);
        foreach ($this->steps as $weight) {
            $colors[] = $this->colorModifier->shade((float)$weight);
        }

        return $colors;
    }
}
