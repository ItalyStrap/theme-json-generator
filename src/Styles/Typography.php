<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

final class Typography implements ArrayableInterface, \JsonSerializable
{
    use CommonTrait;

    /**
     * @var string
     */
    public const FONT_FAMILY = 'fontFamily';

    /**
     * @var string
     */
    public const FONT_SIZE = 'fontSize';

    /**
     * @var string
     */
    public const FONT_STYLE = 'fontStyle';

    /**
     * @var string
     */
    public const FONT_WEIGHT = 'fontWeight';

    /**
     * @var string
     */
    public const LETTER_SPACING = 'letterSpacing';

    /**
     * @var string
     */
    public const LINE_HEIGHT = 'lineHeight';

    /**
     * @var string
     */
    public const TEXT_INDENT = 'textIndent';

    /**
     * @var string
     */
    public const TEXT_ALIGN = 'textAlign';

    /**
     * @var string
     */
    public const TEXT_COLUMNS = 'textColumns';

    /**
     * @var string
     */
    public const TEXT_DECORATION = 'textDecoration';

    /**
     * @var string
     */
    public const WRITING_MODE = 'writingMode';

    /**
     * @var string
     */
    public const TEXT_TRANSFORM = 'textTransform';

    public function fontFamily(string $value): self
    {
        return $this->setProperty(self::FONT_FAMILY, $value);
    }

    public function fontSize(string $value): self
    {
        return $this->setProperty(self::FONT_SIZE, $value);
    }

    public function fontStyle(string $value): self
    {
        return $this->setProperty(self::FONT_STYLE, $value);
    }

    public function fontWeight(string $value): self
    {
        return $this->setProperty(self::FONT_WEIGHT, $value);
    }

    public function letterSpacing(string $value): self
    {
        return $this->setProperty(self::LETTER_SPACING, $value);
    }

    public function lineHeight(string $value): self
    {
        return $this->setProperty(self::LINE_HEIGHT, $value);
    }

    public function textIndent(string $value): self
    {
        return $this->setProperty(self::TEXT_INDENT, $value);
    }

    public function textAlign(string $value): self
    {
        return $this->setProperty(self::TEXT_ALIGN, $value);
    }

    public function textColumns(string $value): self
    {
        return $this->setProperty(self::TEXT_COLUMNS, $value);
    }

    public function textDecoration(string $value): self
    {
        return $this->setProperty(self::TEXT_DECORATION, $value);
    }

    public function writingMode(string $value): self
    {
        return $this->setProperty(self::WRITING_MODE, $value);
    }

    public function textTransform(string $value): self
    {
        return $this->setProperty(self::TEXT_TRANSFORM, $value);
    }
}
