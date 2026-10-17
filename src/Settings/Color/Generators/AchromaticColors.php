<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final class AchromaticColors implements ColorsGenerator
{
    public function generate(): array
    {
        return \array_map(
            static fn (string $color): CssColorInterface => new CssColor($color),
            ['#000000', '#ffffff']
        );
    }
}
