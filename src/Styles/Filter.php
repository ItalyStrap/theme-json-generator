<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Styles;

final class Filter implements ArrayableInterface, \JsonSerializable
{
    use CommonTrait;

    public const SECTION = 'filter';

    /**
     * @var string
     */
    public const DUOTONE = 'duotone';

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::DUOTONE])]
    public function duotone(string $value): self
    {
        return $this->setProperty(self::DUOTONE, $value);
    }
}
