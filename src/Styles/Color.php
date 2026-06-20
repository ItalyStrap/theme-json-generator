<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;

final class Color implements ArrayableInterface, \JsonSerializable
{
    use CommonTrait;

    /**
     * @var string
     */
    public const BACKGROUND = 'background';

    /**
     * @var string
     */
    public const GRADIENT = 'gradient';

    /**
     * @var string
     */
    public const TEXT = 'text';

    #[ThemeSchemaCoverage(['styles', 'color', 'background'])]
    public function background(string $value): Color
    {
        return $this->setProperty(self::BACKGROUND, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'color', 'gradient'])]
    public function gradient(string $value): Color
    {
        return $this->setProperty(self::GRADIENT, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'color', 'text'])]
    public function text(string $value): Color
    {
        return $this->setProperty(self::TEXT, $value);
    }
}
