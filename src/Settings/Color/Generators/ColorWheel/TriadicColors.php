<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorsGenerator;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final readonly class TriadicColors implements ColorsGenerator
{
    use ColorWheelRotationTrait;

    /**
     * @param list<string>|null $identifiers Base, positive rotation, negative rotation.
     */
    public function __construct(
        private CssColorInterface $color,
        private int $angle = 120,
        private ?array $identifiers = null
    ) {
        $this->assertValidIdentifiers($this->identifiers, 3);
    }

    public function generate(): array
    {
        return $this->colorsRotatedBy($this->color, [
            $this->angle,
            -$this->angle,
        ], $this->identifiers);
    }
}
