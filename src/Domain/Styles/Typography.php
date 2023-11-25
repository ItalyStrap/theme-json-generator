<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Styles;

/**
 * @psalm-api
 */
final class Typography implements ArrayableInterface
{
    use CommonTrait;

    public const FONT_FAMILY       = 'fontFamily';
    public const FONT_SIZE         = 'fontSize';
    public const FONT_STYLE        = 'fontStyle';
    public const FONT_WEIGHT       = 'fontWeight';
    public const LETTER_SPACING    = 'letterSpacing';
    public const LINE_HEIGHT       = 'lineHeight';
    public const TEXT_DECORATION   = 'textDecoration';
    public const TEXT_TRANSFORM    = 'textTransform';

    public function fontFamily(string $value): self
    {
        $this->setProperty(self::FONT_FAMILY, $value);
        return $this;
    }

    public function fontSize(string $value): self
    {
        $this->setProperty(self::FONT_SIZE, $value);
        return $this;
    }

    public function fontStyle(string $value): self
    {
        $this->setProperty(self::FONT_STYLE, $value);
        return $this;
    }

    public function fontWeight(string $value): self
    {
        $this->setProperty(self::FONT_WEIGHT, $value);
        return $this;
    }

    public function letterSpacing(string $value): self
    {
        $this->setProperty(self::LETTER_SPACING, $value);
        return $this;
    }

    public function lineHeight(string $value): self
    {
        $this->setProperty(self::LINE_HEIGHT, $value);
        return $this;
    }

    public function textDecoration(string $value): self
    {
        $this->setProperty(self::TEXT_DECORATION, $value);
        return $this;
    }

    public function textTransform(string $value): self
    {
        $this->setProperty(self::TEXT_TRANSFORM, $value);
        return $this;
    }
}
