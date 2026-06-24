<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Styles;

final class Typography implements ArrayableInterface, \JsonSerializable
{
    use CommonTrait;

    public const SECTION = 'typography';

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

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::FONT_FAMILY])]
    public function fontFamily(string $value): self
    {
        return $this->setProperty(self::FONT_FAMILY, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::FONT_SIZE])]
    public function fontSize(string $value): self
    {
        return $this->setProperty(self::FONT_SIZE, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::FONT_STYLE])]
    public function fontStyle(string $value): self
    {
        return $this->setProperty(self::FONT_STYLE, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::FONT_WEIGHT])]
    public function fontWeight(string $value): self
    {
        return $this->setProperty(self::FONT_WEIGHT, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::LETTER_SPACING])]
    public function letterSpacing(string $value): self
    {
        return $this->setProperty(self::LETTER_SPACING, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::LINE_HEIGHT])]
    public function lineHeight(string $value): self
    {
        return $this->setProperty(self::LINE_HEIGHT, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::TEXT_INDENT])]
    public function textIndent(string $value): self
    {
        return $this->setProperty(self::TEXT_INDENT, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::TEXT_ALIGN])]
    public function textAlign(string $value): self
    {
        return $this->setProperty(self::TEXT_ALIGN, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::TEXT_COLUMNS])]
    public function textColumns(string $value): self
    {
        return $this->setProperty(self::TEXT_COLUMNS, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::TEXT_DECORATION])]
    public function textDecoration(string $value): self
    {
        return $this->setProperty(self::TEXT_DECORATION, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::WRITING_MODE])]
    public function writingMode(string $value): self
    {
        return $this->setProperty(self::WRITING_MODE, $value);
    }

    #[ThemeSchemaCoverage([Styles::SECTION, self::SECTION, self::TEXT_TRANSFORM])]
    public function textTransform(string $value): self
    {
        return $this->setProperty(self::TEXT_TRANSFORM, $value);
    }
}
