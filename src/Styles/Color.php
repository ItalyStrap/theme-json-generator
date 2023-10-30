<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

/**
 * @psalm-api
 */
final class Color implements ArrayableInterface, StylesInterface
{
    use ImmutableCollectionTrait;
    use CollectionToArray;
    use UserDefinedPropertyTrait;

    public const BACKGROUND = 'background';
    public const GRADIENT = 'gradient';
    public const TEXT = 'text';

    public function background(string $value): self
    {
        $this->setCollection(self::BACKGROUND, $value);
        return $this;
    }

    public function gradient(string $value): self
    {
        $this->setCollection(self::GRADIENT, $value);
        return $this;
    }

    public function text(string $value): self
    {
        $this->setCollection(self::TEXT, $value);
        return $this;
    }
}
