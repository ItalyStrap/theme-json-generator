<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles;

/**
 * @psalm-api
 */
class Outline implements ArrayableInterface, \JsonSerializable
{
    use CommonTrait;

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

    public function color(string $value): Outline
    {
        return $this->setProperty(self::COLOR, $value);
    }

    public function offset(string $value): Outline
    {
        return $this->setProperty(self::OFFSET, $value);
    }

    public function style(string $value): Outline
    {
        return $this->setProperty(self::STYLE, $value);
    }

    public function width(string $value): Outline
    {
        return $this->setProperty(self::WIDTH, $value);
    }
}
