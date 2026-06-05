<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;

final class Filter implements ArrayableInterface, \JsonSerializable
{
    use CommonTrait;

    /**
     * @var string
     */
    public const DUOTONE = 'duotone';

    #[ThemeSchemaCoverage(['styles', 'filter', 'duotone'])]
    public function duotone(string $value): self
    {
        return $this->setProperty(self::DUOTONE, $value);
    }
}
