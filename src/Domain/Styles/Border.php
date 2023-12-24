<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Styles;

/**
 * @psalm-api
 */
final class Border implements ArrayableInterface
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

    public function color(string $value): self
    {
        $this->setProperty(self::COLOR, $value);
        return $this;
    }

    public function radius(string $value): self
    {
        $this->setProperty(self::RADIUS, $value);
        return $this;
    }

    public function style(string $value): self
    {
        $this->setProperty(self::STYLE, $value);
        return $this;
    }

    public function width(string $value): self
    {
        $this->setProperty(self::WIDTH, $value);
        return $this;
    }
}
