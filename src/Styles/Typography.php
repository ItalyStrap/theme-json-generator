<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;

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

    #[ThemeSchemaCoverage(['styles', 'typography', 'fontFamily'])]
    public function fontFamily(string $value): self
    {
        return $this->setProperty(self::FONT_FAMILY, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'typography', 'fontSize'])]
    public function fontSize(string $value): self
    {
        return $this->setProperty(self::FONT_SIZE, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'typography', 'fontStyle'])]
    public function fontStyle(string $value): self
    {
        return $this->setProperty(self::FONT_STYLE, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'typography', 'fontWeight'])]
    public function fontWeight(string $value): self
    {
        return $this->setProperty(self::FONT_WEIGHT, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'typography', 'letterSpacing'])]
    public function letterSpacing(string $value): self
    {
        return $this->setProperty(self::LETTER_SPACING, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'typography', 'lineHeight'])]
    public function lineHeight(string $value): self
    {
        return $this->setProperty(self::LINE_HEIGHT, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'typography', 'textIndent'])]
    public function textIndent(string $value): self
    {
        return $this->setProperty(self::TEXT_INDENT, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'typography', 'textAlign'])]
    public function textAlign(string $value): self
    {
        return $this->setProperty(self::TEXT_ALIGN, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'typography', 'textColumns'])]
    public function textColumns(string $value): self
    {
        return $this->setProperty(self::TEXT_COLUMNS, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'typography', 'textDecoration'])]
    public function textDecoration(string $value): self
    {
        return $this->setProperty(self::TEXT_DECORATION, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'typography', 'writingMode'])]
    public function writingMode(string $value): self
    {
        return $this->setProperty(self::WRITING_MODE, $value);
    }

    #[ThemeSchemaCoverage(['styles', 'typography', 'textTransform'])]
    public function textTransform(string $value): self
    {
        return $this->setProperty(self::TEXT_TRANSFORM, $value);
    }
}
