<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Styles;

final class Color implements ArrayableInterface, \JsonSerializable
{
    use CommonTrait;

    public const SECTION = 'color';

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

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::BACKGROUND])]
    public function background(string $value): Color
    {
        return $this->setProperty(self::BACKGROUND, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::GRADIENT])]
    public function gradient(string $value): Color
    {
        return $this->setProperty(self::GRADIENT, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::TEXT])]
    public function text(string $value): Color
    {
        return $this->setProperty(self::TEXT, $value);
    }
}
