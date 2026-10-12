<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\Monochromatic;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorsGenerator;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Transformers\ColorModifier;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\ColorVariationStep;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\VariationDirection;

/**
 * Amounts are RGB mixing percentages: lighter mixes with white, darker with black.
 * Each step applies to the original color. Output follows the supplied step order,
 * with no implicit base color or removal of equal color values.
 */
final readonly class TintAndShades implements ColorsGenerator
{
    /**
     * @param list<ColorVariationStep> $steps
     */
    public function __construct(
        private CssColorInterface $color,
        private array $steps
    ) {
        $this->assertUniqueIdentifiers($steps);
    }

    /**
     * @return array<array-key, CssColorInterface>
     */
    public function generate(): array
    {
        $modifier = new ColorModifier($this->color);
        $colors = [];

        foreach ($this->steps as $step) {
            $colors[$step->identifier] = $this->colorForStep($step, $modifier);
        }

        return $colors;
    }

    private function colorForStep(ColorVariationStep $step, ColorModifier $modifier): CssColorInterface
    {
        if ($step->amount === 0.0) {
            return $this->color;
        }

        return match ($step->direction) {
            VariationDirection::Lighter => $modifier->tint($step->amount),
            VariationDirection::Darker => $modifier->shade($step->amount),
        };
    }

    /**
     * @param list<ColorVariationStep> $steps
     */
    private function assertUniqueIdentifiers(array $steps): void
    {
        $identifiers = [];

        foreach ($steps as $step) {
            if (\array_key_exists($step->identifier, $identifiers)) {
                throw new \InvalidArgumentException('Step identifiers must be unique.');
            }

            $identifiers[$step->identifier] = true;
        }
    }
}
