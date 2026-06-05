<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;

final class Border implements ArrayableInterface, \JsonSerializable
{
    use CommonTrait;

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

    #[ThemeSchemaCoverage(['styles', 'border', 'color'])]
    #[ThemeSchemaCoverage(['styles', 'border', 'top', 'color'])]
    #[ThemeSchemaCoverage(['styles', 'border', 'right', 'color'])]
    #[ThemeSchemaCoverage(['styles', 'border', 'bottom', 'color'])]
    #[ThemeSchemaCoverage(['styles', 'border', 'left', 'color'])]
    public function color(string $value): Border
    {
        return $this->setProperty(self::COLOR, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'border', 'radius'])]
    public function radius(string $value): Border
    {
        return $this->setProperty(self::RADIUS, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'border', 'style'])]
    #[ThemeSchemaCoverage(['styles', 'border', 'top', 'style'])]
    #[ThemeSchemaCoverage(['styles', 'border', 'right', 'style'])]
    #[ThemeSchemaCoverage(['styles', 'border', 'bottom', 'style'])]
    #[ThemeSchemaCoverage(['styles', 'border', 'left', 'style'])]
    public function style(string $value): Border
    {
        return $this->setProperty(self::STYLE, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'border', 'width'])]
    #[ThemeSchemaCoverage(['styles', 'border', 'top', 'width'])]
    #[ThemeSchemaCoverage(['styles', 'border', 'right', 'width'])]
    #[ThemeSchemaCoverage(['styles', 'border', 'bottom', 'width'])]
    #[ThemeSchemaCoverage(['styles', 'border', 'left', 'width'])]
    public function width(string $value): Border
    {
        return $this->setProperty(self::WIDTH, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'border', 'top'])]
    public function top(): Border
    {
        return $this->at(self::TOP);
    }

    #[ThemeSchemaCoverage(['styles', 'border', 'right'])]
    public function right(): Border
    {
        return $this->at(self::RIGHT);
    }

    #[ThemeSchemaCoverage(['styles', 'border', 'bottom'])]
    public function bottom(): Border
    {
        return $this->at(self::BOTTOM);
    }

    #[ThemeSchemaCoverage(['styles', 'border', 'left'])]
    public function left(): Border
    {
        return $this->at(self::LEFT);
    }
}
