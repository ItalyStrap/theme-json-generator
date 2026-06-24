<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\Fluid;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\FontFamily;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\FontSize;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\Utilities\Fluid as FontSizeFluid;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\Utilities\FontFace;

final readonly class Typography
{
    use ScopedSettingsWriterTrait;

    public const SECTION = 'typography';

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

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::DEFAULT_FONT_SIZES])]
    public function enableDefaultFontSizes(): self
    {
        return $this->setBoolean(self::DEFAULT_FONT_SIZES, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::DEFAULT_FONT_SIZES])]
    public function disableDefaultFontSizes(): self
    {
        return $this->setBoolean(self::DEFAULT_FONT_SIZES, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::CUSTOM_FONT_SIZE])]
    public function enableCustomFontSize(): self
    {
        return $this->setBoolean(self::CUSTOM_FONT_SIZE, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::CUSTOM_FONT_SIZE])]
    public function disableCustomFontSize(): self
    {
        return $this->setBoolean(self::CUSTOM_FONT_SIZE, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::FONT_STYLE])]
    public function enableFontStyle(): self
    {
        return $this->setBoolean(self::FONT_STYLE, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::FONT_STYLE])]
    public function disableFontStyle(): self
    {
        return $this->setBoolean(self::FONT_STYLE, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::FONT_WEIGHT])]
    public function enableFontWeight(): self
    {
        return $this->setBoolean(self::FONT_WEIGHT, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::FONT_WEIGHT])]
    public function disableFontWeight(): self
    {
        return $this->setBoolean(self::FONT_WEIGHT, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::FLUID])]
    public function enableFluid(): self
    {
        return $this->setFluidBoolean(true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::FLUID])]
    public function disableFluid(): self
    {
        return $this->setFluidBoolean(false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, Fluid::SECTION])]
    public function fluid(): Fluid
    {
        return new Fluid($this);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::LETTER_SPACING])]
    public function enableLetterSpacing(): self
    {
        return $this->setBoolean(self::LETTER_SPACING, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::LETTER_SPACING])]
    public function disableLetterSpacing(): self
    {
        return $this->setBoolean(self::LETTER_SPACING, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::LINE_HEIGHT])]
    public function enableLineHeight(): self
    {
        return $this->setBoolean(self::LINE_HEIGHT, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::LINE_HEIGHT])]
    public function disableLineHeight(): self
    {
        return $this->setBoolean(self::LINE_HEIGHT, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::TEXT_INDENT])]
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

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::TEXT_INDENT])]
    public function disableTextIndent(): self
    {
        return $this->setBoolean(self::TEXT_INDENT, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::TEXT_ALIGN])]
    public function enableTextAlign(): self
    {
        return $this->setBoolean(self::TEXT_ALIGN, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::TEXT_ALIGN])]
    public function disableTextAlign(): self
    {
        return $this->setBoolean(self::TEXT_ALIGN, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::TEXT_COLUMNS])]
    public function enableTextColumns(): self
    {
        return $this->setBoolean(self::TEXT_COLUMNS, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::TEXT_COLUMNS])]
    public function disableTextColumns(): self
    {
        return $this->setBoolean(self::TEXT_COLUMNS, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::TEXT_DECORATION])]
    public function enableTextDecoration(): self
    {
        return $this->setBoolean(self::TEXT_DECORATION, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::TEXT_DECORATION])]
    public function disableTextDecoration(): self
    {
        return $this->setBoolean(self::TEXT_DECORATION, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::WRITING_MODE])]
    public function enableWritingMode(): self
    {
        return $this->setBoolean(self::WRITING_MODE, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::WRITING_MODE])]
    public function disableWritingMode(): self
    {
        return $this->setBoolean(self::WRITING_MODE, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::TEXT_TRANSFORM])]
    public function enableTextTransform(): self
    {
        return $this->setBoolean(self::TEXT_TRANSFORM, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::TEXT_TRANSFORM])]
    public function disableTextTransform(): self
    {
        return $this->setBoolean(self::TEXT_TRANSFORM, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::DROP_CAP])]
    public function enableDropCap(): self
    {
        return $this->setBoolean(self::DROP_CAP, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::DROP_CAP])]
    public function disableDropCap(): self
    {
        return $this->setBoolean(self::DROP_CAP, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, FontFamily::SECTION])]
    public function addFontFamily(string $slug, string $name, string $fontFamily, FontFace ...$fontFace): self
    {
        $this->settings->addPreset(new FontFamily($slug, $name, $fontFamily, ...$fontFace));

        return $this;
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, FontSize::SECTION])]
    public function addFontSize(
        string $slug,
        string $name,
        string $size,
        FontSizeFluid|false|null $fluid = null
    ): self {
        $this->guardAgainstFluidClampConflict($size, $fluid);
        $this->settings->addPreset(new FontSize($slug, $name, $size, $fluid));

        return $this;
    }

    public function writeFluidConfig(string $property, string $value): bool
    {
        $fluid = $this->settings->read([self::SECTION, self::FLUID]);

        if (\is_bool($fluid)) {
            throw new \LogicException(
                'Global fluid typography is already configured as a boolean and cannot be replaced with an object.'
            );
        }

        $this->guardAgainstRegisteredClampFontSizes();

        return $this->set([self::FLUID, $property], $value);
    }

    private function setFluidBoolean(bool $value): self
    {
        $fluid = $this->settings->read([self::SECTION, self::FLUID]);

        if (\is_array($fluid)) {
            throw new \LogicException(
                'Global fluid typography is already configured and cannot be replaced with a boolean.'
            );
        }

        if ($value) {
            $this->guardAgainstRegisteredClampFontSizes();
        }

        return $this->setBoolean(self::FLUID, $value);
    }

    private function guardAgainstFluidClampConflict(
        string $size,
        FontSizeFluid|false|null $fontSizeFluid
    ): void {
        if ($fontSizeFluid === false) {
            return;
        }

        if (!$this->containsClamp($size)) {
            return;
        }

        $fluid = $this->settings->read([self::SECTION, self::FLUID], false);

        if ($fluid === false || $fluid === null) {
            return;
        }

        throw new \InvalidArgumentException(
            'Fluid typography cannot be applied to a font size that already uses clamp().'
        );
    }

    private function guardAgainstRegisteredClampFontSizes(): void
    {
        if (!$this->containsConflictingFontSize($this->settings->readPresets(FontSize::TYPE))) {
            return;
        }

        throw new \InvalidArgumentException(
            'Fluid typography cannot be applied to a font size that already uses clamp().'
        );
    }

    private function containsConflictingFontSize(mixed $fontSizes): bool
    {
        if ($fontSizes instanceof FontSize) {
            $fontSize = $fontSizes->toArray();

            return ($fontSize['fluid'] ?? null) !== false
                && $this->containsClamp($fontSize['size']);
        }

        if (!\is_array($fontSizes)) {
            return false;
        }

        foreach ($fontSizes as $fontSize) {
            if ($this->containsConflictingFontSize($fontSize)) {
                return true;
            }
        }

        return false;
    }

    private function containsClamp(string $value): bool
    {
        return \stripos($value, 'clamp(') !== false;
    }
}
