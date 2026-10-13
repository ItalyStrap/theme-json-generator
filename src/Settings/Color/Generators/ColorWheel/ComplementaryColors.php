<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorsGenerator;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final readonly class ComplementaryColors implements ColorsGenerator
{
    use ColorWheelRotationTrait;

    /**
     * @param list<string>|null $identifiers Base, rotated color.
     */
    public function __construct(
        private CssColorInterface $color,
        private int $angle = 180,
        private ?array $identifiers = null
    ) {
        $this->validateIdentifiers($this->identifiers, 2);
    }

    public function generate(): array
    {
        return $this->colorsRotatedBy($this->color, [
            $this->angle,
        ], $this->identifiers);
    }
}
