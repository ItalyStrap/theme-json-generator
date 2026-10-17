<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Styles;

final class Outline implements ArrayableInterface, \JsonSerializable
{
    use CommonTrait;

    public const SECTION = 'outline';

    /**
     * @var string
     */
    public const COLOR = 'color';

    /**
     * @var string
     */
    public const OFFSET = 'offset';

    /**
     * @var string
     */
    public const STYLE = 'style';

    /**
     * @var string
     */
    public const WIDTH = 'width';

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::COLOR])]
    public function color(string $value): Outline
    {
        return $this->setProperty(self::COLOR, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::OFFSET])]
    public function offset(string $value): Outline
    {
        return $this->setProperty(self::OFFSET, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::STYLE])]
    public function style(string $value): Outline
    {
        return $this->setProperty(self::STYLE, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::WIDTH])]
    public function width(string $value): Outline
    {
        return $this->setProperty(self::WIDTH, $value);
    }
}
