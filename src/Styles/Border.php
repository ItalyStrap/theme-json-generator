<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

final class Border implements ArrayableInterface, StylesInterface
{
    use ImmutableCollectionTrait;
    use CollectionToArray;
    use UserDefinedPropertyTrait;

    public const COLOR = 'color';
    public const RADIUS = 'radius';
    public const STYLE = 'style';
    public const WIDTH = 'width';

    public function color(string $value): self
    {
        $this->setCollection(self::COLOR, $value);
        return $this;
    }

    public function radius(string $value): self
    {
        $this->setCollection(self::RADIUS, $value);
        return $this;
    }

    public function style(string $value): self
    {
        $this->setCollection(self::STYLE, $value);
        return $this;
    }

    public function width(string $value): self
    {
        $this->setCollection(self::WIDTH, $value);
        return $this;
    }
}
