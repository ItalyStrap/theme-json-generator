<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings;
use ItalyStrap\ThemeJsonGenerator\Settings\Spacing\Scale;
use ItalyStrap\ThemeJsonGenerator\Settings\Spacing\SpacingSize;

final readonly class Spacing
{
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
        $this->set(self::BLOCK_GAP, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'blockGap'])]
    public function disableBlockGap(): self
    {
        $this->set(self::BLOCK_GAP, false);
        return $this;
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
        $this->set(self::MARGIN, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'margin'])]
    public function disableMargin(): self
    {
        $this->set(self::MARGIN, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'padding'])]
    public function enablePadding(): self
    {
        $this->set(self::PADDING, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'padding'])]
    public function disablePadding(): self
    {
        $this->set(self::PADDING, false);
        return $this;
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
        $this->set(self::CUSTOM_SPACING_SIZE, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'customSpacingSize'])]
    public function disableCustomSpacingSize(): self
    {
        $this->set(self::CUSTOM_SPACING_SIZE, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'defaultSpacingSizes'])]
    public function enableDefaultSpacingSizes(): self
    {
        $this->set(self::DEFAULT_SPACING_SIZES, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'defaultSpacingSizes'])]
    public function disableDefaultSpacingSizes(): self
    {
        $this->set(self::DEFAULT_SPACING_SIZES, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'spacingSizes'])]
    public function addSpacingSize(string $slug, string $name, string $size): self
    {
        $this->settings->addPreset(
            ['spacing', self::SPACING_SIZES],
            new SpacingSize($slug, $name, $size)
        );

        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'spacing', 'spacingScale'])]
    public function scale(): Scale
    {
        return new Scale($this);
    }

    /**
     * @param array<array-key, string|int>|string $path
     */
    public function set(array|string $path, mixed $value): bool
    {
        if (\is_string($path)) {
            $path = \explode('.', $path);
        }

        return $this->settings->set(['spacing', ...$path], $value);
    }
}
