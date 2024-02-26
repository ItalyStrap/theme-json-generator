<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities;

/**
 * @psalm-api
 */
interface ColorsGenerator
{
    /**
     * @return array<array-key, ColorInterface>
     */
    public function generate(): array;
}
