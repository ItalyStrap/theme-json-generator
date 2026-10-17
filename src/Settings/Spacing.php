<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings;
use ItalyStrap\ThemeJsonGenerator\Settings\Spacing\Scale;
use ItalyStrap\ThemeJsonGenerator\Settings\Spacing\SpacingSize;

final readonly class Spacing
{
    use ScopedSettingsWriterTrait;

    public const SECTION = 'spacing';

    public const BLOCK_GAP = 'blockGap';

    public const CUSTOM_SPACING_SIZE = 'customSpacingSize';

    public const DEFAULT_SPACING_SIZES = 'defaultSpacingSizes';

    public const MARGIN = 'margin';

    public const PADDING = 'padding';

    public const SPACING_SCALE = 'spacingScale';

    public const UNITS = 'units';

    public function __construct(
        private Settings $settings,
    ) {
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::BLOCK_GAP])]
    public function enableBlockGap(): self
    {
        return $this->setBoolean(self::BLOCK_GAP, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::BLOCK_GAP])]
    public function disableBlockGap(): self
    {
        return $this->setBoolean(self::BLOCK_GAP, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::BLOCK_GAP])]
    public function disableBlockGapAndLayoutStyles(): self
    {
        $this->set(self::BLOCK_GAP, null);
        return $this;
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::MARGIN])]
    public function enableMargin(): self
    {
        return $this->setBoolean(self::MARGIN, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::MARGIN])]
    public function disableMargin(): self
    {
        return $this->setBoolean(self::MARGIN, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::PADDING])]
    public function enablePadding(): self
    {
        return $this->setBoolean(self::PADDING, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::PADDING])]
    public function disablePadding(): self
    {
        return $this->setBoolean(self::PADDING, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::UNITS])]
    public function units(string ...$units): self
    {
        if ($units === []) {
            throw new \InvalidArgumentException('Expected at least one spacing unit.');
        }

        $this->set(self::UNITS, $units);
        return $this;
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::CUSTOM_SPACING_SIZE])]
    public function enableCustomSpacingSize(): self
    {
        return $this->setBoolean(self::CUSTOM_SPACING_SIZE, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::CUSTOM_SPACING_SIZE])]
    public function disableCustomSpacingSize(): self
    {
        return $this->setBoolean(self::CUSTOM_SPACING_SIZE, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::DEFAULT_SPACING_SIZES])]
    public function enableDefaultSpacingSizes(): self
    {
        return $this->setBoolean(self::DEFAULT_SPACING_SIZES, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::DEFAULT_SPACING_SIZES])]
    public function disableDefaultSpacingSizes(): self
    {
        return $this->setBoolean(self::DEFAULT_SPACING_SIZES, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, SpacingSize::SECTION])]
    public function addSpacingSize(string $slug, string $name, string $size): self
    {
        $this->settings->addPreset(new SpacingSize($slug, $name, $size));

        return $this;
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, Scale::SECTION])]
    public function scale(): Scale
    {
        return new Scale($this);
    }

    public function writeScale(string $property, mixed $value): bool
    {
        return $this->set([self::SPACING_SCALE, $property], $value);
    }
}
