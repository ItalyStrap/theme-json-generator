<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Handler;

/**
 * @psalm-api
 */
final class AchromaticColorScheme implements SchemeInterface
{
    public function generate(): iterable
    {
        foreach (['#000000', '#ffffff'] as $color) {
            yield new ColorValue($color);
        }
    }
}
