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

    private const SECTION = 'spacing';

    public const BLOCK_GAP = 'blockGap';

    public const CUSTOM_SPACING_SIZE = 'customSpacingSize';

    public const DEFAULT_SPACING_SIZES = 'defaultSpacingSizes';

    public const MARGIN = 'margin';

    public const PADDING = 'padding';

    public const SPACING_SCALE = 'spacingScale';

    public const SPACING_SIZES = 'spacingSizes';

    public const UNITS = 'units';

    public function __construct(
        private Settings $settings,
    ) {
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'blockGap'])]
    public function enableBlockGap(): self
    {
        return $this->setBoolean(self::BLOCK_GAP, true);
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'blockGap'])]
    public function disableBlockGap(): self
    {
        return $this->setBoolean(self::BLOCK_GAP, false);
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'blockGap'])]
    public function disableBlockGapAndLayoutStyles(): self
    {
        $this->set(self::BLOCK_GAP, null);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'margin'])]
    public function enableMargin(): self
    {
        return $this->setBoolean(self::MARGIN, true);
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'margin'])]
    public function disableMargin(): self
    {
        return $this->setBoolean(self::MARGIN, false);
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'padding'])]
    public function enablePadding(): self
    {
        return $this->setBoolean(self::PADDING, true);
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'padding'])]
    public function disablePadding(): self
    {
        return $this->setBoolean(self::PADDING, false);
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'units'])]
    public function units(string ...$units): self
    {
        if ($units === []) {
            throw new \InvalidArgumentException('Expected at least one spacing unit.');
        }

        $this->set(self::UNITS, $units);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'customSpacingSize'])]
    public function enableCustomSpacingSize(): self
    {
        return $this->setBoolean(self::CUSTOM_SPACING_SIZE, true);
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'customSpacingSize'])]
    public function disableCustomSpacingSize(): self
    {
        return $this->setBoolean(self::CUSTOM_SPACING_SIZE, false);
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'defaultSpacingSizes'])]
    public function enableDefaultSpacingSizes(): self
    {
        return $this->setBoolean(self::DEFAULT_SPACING_SIZES, true);
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'defaultSpacingSizes'])]
    public function disableDefaultSpacingSizes(): self
    {
        return $this->setBoolean(self::DEFAULT_SPACING_SIZES, false);
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'spacingSizes'])]
    public function addSpacingSize(string $slug, string $name, string $size): self
    {
        $this->settings->addPreset(new SpacingSize($slug, $name, $size));

        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'spacingScale'])]
    public function scale(): Scale
    {
        return new Scale($this);
    }

    public function writeScale(string $property, mixed $value): bool
    {
        return $this->set([self::SPACING_SCALE, $property], $value);
    }
}
