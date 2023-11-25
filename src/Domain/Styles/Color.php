<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Styles;

/**
 * @psalm-api
 */
final class Color implements ArrayableInterface
{
    use CommonTrait;

    public const BACKGROUND = 'background';
    public const GRADIENT = 'gradient';
    public const TEXT = 'text';

    public function background(string $value): self
    {
        $this->setProperty(self::BACKGROUND, $value);
        return $this;
    }

    public function gradient(string $value): self
    {
        $this->setProperty(self::GRADIENT, $value);
        return $this;
    }

    public function text(string $value): self
    {
        $this->setProperty(self::TEXT, $value);
        return $this;
    }
}
