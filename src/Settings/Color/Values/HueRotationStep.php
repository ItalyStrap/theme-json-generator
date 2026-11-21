<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Values;

/**
 * Signed degrees rotate the original hue; the identifier is the output array key.
 */
final readonly class HueRotationStep
{
    public function __construct(
        public string $identifier,
        public int $degrees
    ) {
        if (\trim($identifier, " \n\r\t\v\x00") === '') {
            throw new \InvalidArgumentException('Step identifier must not be blank.');
        }
    }
}
