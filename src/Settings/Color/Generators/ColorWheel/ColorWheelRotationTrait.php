<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\HueRotationStep;

/**
 * Adapts the harmony generators' base-first numeric palettes to explicit hue steps.
 */
trait ColorWheelRotationTrait
{
    /**
     * Identifiers follow base-first rotation order. Defaults use numeric keys and
     * skip zero rotations; explicit identifiers retain every position and alias.
     * HueRotation normalizes degrees and retains the original instance for full turns.
     *
     * @param int[] $degrees
     * @param list<string>|null $identifiers
     * @return array<array-key, CssColorInterface>
     */
    private function colorsRotatedBy(
        CssColorInterface $color,
        array $degrees,
        ?array $identifiers = null
    ): array {
        $rotations = [0];
        foreach ($degrees as $degree) {
            if ($identifiers === null && $degree === 0) {
                continue;
            }

            $rotations[] = $degree;
        }

        $this->assertValidIdentifiers($identifiers, \count($rotations));
        $identifiers ??= \array_map(static fn (int $index): string => (string) $index, \array_keys($rotations));

        $steps = [];
        foreach ($rotations as $index => $degree) {
            $steps[] = new HueRotationStep($identifiers[$index], $degree);
        }

        return (new HueRotation($color, $steps))->generate();
    }

    /**
     * @param array<array-key, mixed>|null $identifiers
     */
    private function assertValidIdentifiers(?array $identifiers, int $expectedCount): void
    {
        if ($identifiers === null) {
            return;
        }

        if (\count($identifiers) !== $expectedCount) {
            throw new \InvalidArgumentException('Identifier count must match the number of color positions.');
        }

        if (!\array_is_list($identifiers)) {
            throw new \InvalidArgumentException('Identifiers must be a list.');
        }

        $seen = [];
        foreach ($identifiers as $identifier) {
            if (!\is_string($identifier)) {
                throw new \InvalidArgumentException('Identifiers must contain only strings.');
            }

            if (\trim($identifier) === '') {
                throw new \InvalidArgumentException('Step identifier must not be blank.');
            }

            if (\array_key_exists($identifier, $seen)) {
                throw new \InvalidArgumentException('Step identifiers must be unique.');
            }

            $seen[$identifier] = true;
        }
    }
}
