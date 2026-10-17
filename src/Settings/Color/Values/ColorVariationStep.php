<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Values;

/**
 * The generator defines the amount's units; the identifier is the output array key.
 */
final readonly class ColorVariationStep
{
    public function __construct(
        public string $identifier,
        public float $amount,
        public VariationDirection $direction
    ) {
        if (\trim($identifier) === '') {
            throw new \InvalidArgumentException('Step identifier must not be blank.');
        }

        if (!\is_finite($amount) || $amount < 0 || $amount > 100) {
            throw new \InvalidArgumentException('Step amount must be finite and between 0 and 100.');
        }
    }
}
