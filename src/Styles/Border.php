<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Styles;

final class Border implements ArrayableInterface, \JsonSerializable
{
    use CommonTrait;

    public const SECTION = 'border';

    /**
     * @var string
     */
    public const COLOR = 'color';

    /**
     * @var string
     */
    public const RADIUS = 'radius';

    /**
     * @var string
     */
    public const STYLE = 'style';

    /**
     * @var string
     */
    public const WIDTH = 'width';

    /**
     * @var string
     */
    public const TOP = 'top';

    /**
     * @var string
     */
    public const RIGHT = 'right';

    /**
     * @var string
     */
    public const BOTTOM = 'bottom';

    /**
     * @var string
     */
    public const LEFT = 'left';

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::COLOR])]
    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::TOP, self::COLOR])]
    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::RIGHT, self::COLOR])]
    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::BOTTOM, self::COLOR])]
    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::LEFT, self::COLOR])]
    public function color(string $value): Border
    {
        return $this->setProperty(self::COLOR, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::RADIUS])]
    public function radius(string $value): Border
    {
        return $this->setProperty(self::RADIUS, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::STYLE])]
    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::TOP, self::STYLE])]
    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::RIGHT, self::STYLE])]
    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::BOTTOM, self::STYLE])]
    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::LEFT, self::STYLE])]
    public function style(string $value): Border
    {
        return $this->setProperty(self::STYLE, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::WIDTH])]
    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::TOP, self::WIDTH])]
    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::RIGHT, self::WIDTH])]
    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::BOTTOM, self::WIDTH])]
    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::LEFT, self::WIDTH])]
    public function width(string $value): Border
    {
        return $this->setProperty(self::WIDTH, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::TOP])]
    public function top(): Border
    {
        return $this->at(self::TOP);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::RIGHT])]
    public function right(): Border
    {
        return $this->at(self::RIGHT);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::BOTTOM])]
    public function bottom(): Border
    {
        return $this->at(self::BOTTOM);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::LEFT])]
    public function left(): Border
    {
        return $this->at(self::LEFT);
    }
}
