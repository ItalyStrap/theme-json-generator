<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorsGenerator;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Transformers\ColorModifierInterface;

final class MonochromaticColorsExperimental implements ColorsGenerator
{
    /**
     * @param array<array-key, int|float> $steps
     */
    public function __construct(
        private readonly ColorModifierInterface $colorModifier,
        private array $steps
    ) {
        $this->assertValidSteps($steps);
    }

    public function generate(): array
    {
        $stepsDescending = $this->steps;
        \rsort($stepsDescending);

        $colors = [];
        foreach ($stepsDescending as $weight) {
            $colors[] = $this->colorModifier->tint((float)$weight);
        }

        $colors[] = $this->colorModifier->tint();

        $stepsAscending = $this->steps;
        \sort($stepsAscending);

        foreach ($stepsAscending as $weight) {
            $colors[] = $this->colorModifier->shade((float)$weight);
        }

        return $colors;
    }

    /**
     * @param array<array-key, int|float> $steps
     */
    private function assertValidSteps(array $steps): void
    {
        foreach ($steps as $step) {
            if ($step < 0 || $step > 100) {
                throw new \InvalidArgumentException('Step must be between 0 and 100.');
            }
        }
    }
}
