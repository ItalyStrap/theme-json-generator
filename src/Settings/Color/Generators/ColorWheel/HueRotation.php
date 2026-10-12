<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorsGenerator;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Transformers\ColorModifier;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\HueRotationStep;

/**
 * Each rotation applies to the original color using ColorModifier's conversion.
 * Output preserves step order and equal values, with no implicit base color.
 * Zero and full turns return the original instance without conversion.
 */
final readonly class HueRotation implements ColorsGenerator
{
    /**
     * @param list<HueRotationStep> $steps
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

    private function colorForStep(HueRotationStep $step, ColorModifier $modifier): CssColorInterface
    {
        $degrees = $step->degrees % 360;
        if ($degrees === 0) {
            return $this->color;
        }

        return $modifier->hueRotate($degrees);
    }

    /**
     * @param list<HueRotationStep> $steps
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
