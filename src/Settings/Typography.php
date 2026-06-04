<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Settings;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\Fluid;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\FontFamily;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\FontSize;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\Utilities\Fluid as FontSizeFluid;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\Utilities\FontFace;

final readonly class Typography
{
    public const CUSTOM_FONT_SIZE = 'customFontSize';

    public const DEFAULT_FONT_SIZES = 'defaultFontSizes';

    public const DROP_CAP = 'dropCap';

    public const FLUID = 'fluid';

    public const FONT_FAMILIES = 'fontFamilies';

    public const FONT_SIZES = 'fontSizes';

    public const FONT_STYLE = 'fontStyle';

    public const FONT_WEIGHT = 'fontWeight';

    public const LETTER_SPACING = 'letterSpacing';

    public const LINE_HEIGHT = 'lineHeight';

    public const TEXT_ALIGN = 'textAlign';

    public const TEXT_COLUMNS = 'textColumns';

    public const TEXT_DECORATION = 'textDecoration';

    public const TEXT_INDENT = 'textIndent';

    public const TEXT_TRANSFORM = 'textTransform';

    public const WRITING_MODE = 'writingMode';

    public function __construct(
        private Settings $settings,
    ) {
    }

    public function enableDefaultFontSizes(): self
    {
        return $this->setBoolean(self::DEFAULT_FONT_SIZES, true);
    }

    public function disableDefaultFontSizes(): self
    {
        return $this->setBoolean(self::DEFAULT_FONT_SIZES, false);
    }

    public function enableCustomFontSize(): self
    {
        return $this->setBoolean(self::CUSTOM_FONT_SIZE, true);
    }

    public function disableCustomFontSize(): self
    {
        return $this->setBoolean(self::CUSTOM_FONT_SIZE, false);
    }

    public function enableFontStyle(): self
    {
        return $this->setBoolean(self::FONT_STYLE, true);
    }

    public function disableFontStyle(): self
    {
        return $this->setBoolean(self::FONT_STYLE, false);
    }

    public function enableFontWeight(): self
    {
        return $this->setBoolean(self::FONT_WEIGHT, true);
    }

    public function disableFontWeight(): self
    {
        return $this->setBoolean(self::FONT_WEIGHT, false);
    }

    public function enableFluid(): self
    {
        return $this->setBoolean(self::FLUID, true);
    }

    public function disableFluid(): self
    {
        return $this->setBoolean(self::FLUID, false);
    }

    public function fluid(): Fluid
    {
        return new Fluid($this);
    }

    public function enableLetterSpacing(): self
    {
        return $this->setBoolean(self::LETTER_SPACING, true);
    }

    public function disableLetterSpacing(): self
    {
        return $this->setBoolean(self::LETTER_SPACING, false);
    }

    public function enableLineHeight(): self
    {
        return $this->setBoolean(self::LINE_HEIGHT, true);
    }

    public function disableLineHeight(): self
    {
        return $this->setBoolean(self::LINE_HEIGHT, false);
    }

    public function textIndent(string $textIndent): self
    {
        if (!\in_array($textIndent, ['subsequent', 'all'], true)) {
            throw new \InvalidArgumentException(
                \sprintf('Expected text indent "subsequent" or "all", got "%s".', $textIndent)
            );
        }

        $this->set(self::TEXT_INDENT, $textIndent);
        return $this;
    }

    public function disableTextIndent(): self
    {
        return $this->setBoolean(self::TEXT_INDENT, false);
    }

    public function enableTextAlign(): self
    {
        return $this->setBoolean(self::TEXT_ALIGN, true);
    }

    public function disableTextAlign(): self
    {
        return $this->setBoolean(self::TEXT_ALIGN, false);
    }

    public function enableTextColumns(): self
    {
        return $this->setBoolean(self::TEXT_COLUMNS, true);
    }

    public function disableTextColumns(): self
    {
        return $this->setBoolean(self::TEXT_COLUMNS, false);
    }

    public function enableTextDecoration(): self
    {
        return $this->setBoolean(self::TEXT_DECORATION, true);
    }

    public function disableTextDecoration(): self
    {
        return $this->setBoolean(self::TEXT_DECORATION, false);
    }

    public function enableWritingMode(): self
    {
        return $this->setBoolean(self::WRITING_MODE, true);
    }

    public function disableWritingMode(): self
    {
        return $this->setBoolean(self::WRITING_MODE, false);
    }

    public function enableTextTransform(): self
    {
        return $this->setBoolean(self::TEXT_TRANSFORM, true);
    }

    public function disableTextTransform(): self
    {
        return $this->setBoolean(self::TEXT_TRANSFORM, false);
    }

    public function enableDropCap(): self
    {
        return $this->setBoolean(self::DROP_CAP, true);
    }

    public function disableDropCap(): self
    {
        return $this->setBoolean(self::DROP_CAP, false);
    }

    public function addFontSize(string $slug, string $name, string $size, ?FontSizeFluid $fluid = null): self
    {
        $this->guardAgainstFluidClampConflict($size);
        $this->settings->addPreset(['typography', self::FONT_SIZES], new FontSize($slug, $name, $size, $fluid));

        return $this;
    }

    public function addFontFamily(string $slug, string $name, string $fontFamily, FontFace ...$fontFace): self
    {
        $this->settings->addPreset(
            ['typography', self::FONT_FAMILIES],
            new FontFamily($slug, $name, $fontFamily, ...$fontFace)
        );

        return $this;
    }

    /**
     * @param array<array-key, string|int>|string $path
     */
    public function set(array|string $path, mixed $value): bool
    {
        if (\is_string($path)) {
            $path = \explode('.', $path);
        }

        return $this->settings->set(['typography', ...$path], $value);
    }

    private function setBoolean(string $property, bool $value): self
    {
        $this->set($property, $value);
        return $this;
    }

    private function guardAgainstFluidClampConflict(string $size): void
    {
        if (!$this->containsClamp($size)) {
            return;
        }

        $fluid = $this->settings->get(['typography', self::FLUID], false);

        if ($fluid === false || $fluid === null) {
            return;
        }

        throw new \InvalidArgumentException(
            'Fluid typography cannot be applied to a font size that already uses clamp().'
        );
    }

    private function containsClamp(string $value): bool
    {
        return \stripos($value, 'clamp(') !== false;
    }
}
