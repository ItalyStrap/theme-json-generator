<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorsGenerator;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Transformers\ColorModifier;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final class MonochromaticColors implements ColorsGenerator
{
    /**
     * @param array<array-key, int|float> $steps
     */
    public function __construct(
        private readonly CssColorInterface $color,
        private array $steps
    ) {
        $this->assertValidSteps($steps);
    }

    public function generate(): array
    {
        $colorModifier = new ColorModifier($this->color);
        $stepsDescending = $this->steps;
        \rsort($stepsDescending);

        $colors = [];
        foreach ($stepsDescending as $weight) {
            $colors[] = $colorModifier->tint((float)$weight);
        }

        $colors[] = $colorModifier->tint();

        $stepsAscending = $this->steps;
        \sort($stepsAscending);

        foreach ($stepsAscending as $weight) {
            $colors[] = $colorModifier->shade((float)$weight);
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
