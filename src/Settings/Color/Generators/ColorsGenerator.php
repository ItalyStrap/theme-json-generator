<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

interface ColorsGenerator
{
    /**
     * @return array<array-key, CssColorInterface>
     */
    public function generate(): array;
}
