<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities;

/**
 * @psalm-api
 */
class AchromaticColorsExperimental implements ColorsGenerator
{
    public function generate(): array
    {
        return \array_map(
            static fn (string $color): ColorInfoInterface => new ColorInfo($color),
            ['#000000', '#ffffff']
        );
    }
}
