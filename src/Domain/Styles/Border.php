<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Styles;

/**
 * @psalm-api
 */
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
}
