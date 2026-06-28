<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorsGenerator;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final class AchromaticColorsExperimental implements ColorsGenerator
{
    public function generate(): array
    {
        return \array_map(
            static fn (string $color): CssColorInterface => new CssColor($color),
            ['#000000', '#ffffff']
        );
    }
}
