<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities;

final class AchromaticColorsExperimental implements ColorsGenerator
{
    public function generate(): array
    {
        return \array_map(
            static fn (string $color): ColorInterface => new Color($color),
            ['#000000', '#ffffff']
        );
    }
}
