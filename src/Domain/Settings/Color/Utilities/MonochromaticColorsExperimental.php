<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities;

/**
 * @psalm-api
 */
class MonochromaticColorsExperimental implements ColorsGenerator
{
    private ColorModifierInterface $colorModifier;

    /**
     * @var array<array-key, int|float>
     */
    private array $steps = [];

    /**
     * @param array<array-key, int|float> $steps
     */
    public function __construct(ColorModifierInterface $color, array $steps)
    {
        $this->colorModifier = $color;
        $this->steps = $steps;
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
