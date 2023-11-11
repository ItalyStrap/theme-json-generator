<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities;

/**
 * @psalm-api
 */
class AchromaticColors implements ColorsGenerator
{
    public function generate(): array
    {
        return \array_map(
            function (string $color): ColorInfoInterface {
                return new ColorInfo($color);
            },
            ['#000000', '#ffffff']
        );
    }
}
