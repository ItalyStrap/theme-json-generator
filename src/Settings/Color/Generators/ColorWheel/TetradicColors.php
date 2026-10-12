<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorsGenerator;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final readonly class TetradicColors implements ColorsGenerator
{
    use ColorWheelRotationTrait;

    /**
     * @param list<string>|null $identifiers Base, first, second, and third angle multiples.
     */
    public function __construct(
        private CssColorInterface $color,
        private int $angle = 90,
        private ?array $identifiers = null
    ) {
        $this->assertValidIdentifiers($this->identifiers, 4);
    }

    public function generate(): array
    {
        return $this->colorsRotatedBy($this->color, [
            $this->angle,
            $this->angle * 2,
            $this->angle * 3,
        ], $this->identifiers);
    }
}
