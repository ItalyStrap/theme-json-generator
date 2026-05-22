<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities;

interface ColorsGenerator
{
    /**
     * @return array<array-key, ColorInterface>
     */
    public function generate(): array;
}
