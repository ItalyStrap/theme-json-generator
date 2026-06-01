<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

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

    public function color(string $value): Border
    {
        return $this->setProperty(self::COLOR, $value);
    }

    public function radius(string $value): Border
    {
        return $this->setProperty(self::RADIUS, $value);
    }

    public function style(string $value): Border
    {
        return $this->setProperty(self::STYLE, $value);
    }

    public function width(string $value): Border
    {
        return $this->setProperty(self::WIDTH, $value);
    }

    public function top(): Border
    {
        return $this->at(self::TOP);
    }

    public function right(): Border
    {
        return $this->at(self::RIGHT);
    }

    public function bottom(): Border
    {
        return $this->at(self::BOTTOM);
    }

    public function left(): Border
    {
        return $this->at(self::LEFT);
    }
}
