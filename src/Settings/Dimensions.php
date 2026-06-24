<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings;
use ItalyStrap\ThemeJsonGenerator\Settings\Dimensions\AspectRatio;
use ItalyStrap\ThemeJsonGenerator\Settings\Dimensions\DimensionSize;

final readonly class Dimensions
{
    use ScopedSettingsWriterTrait;

    public const SECTION = 'dimensions';

    public const ASPECT_RATIO = 'aspectRatio';

    public const DEFAULT_ASPECT_RATIOS = 'defaultAspectRatios';

    public const HEIGHT = 'height';

    public const MIN_HEIGHT = 'minHeight';

    public const MIN_WIDTH = 'minWidth';

    public const WIDTH = 'width';

    public function __construct(
        private Settings $settings,
    ) {
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::ASPECT_RATIO])]
    public function enableAspectRatio(): self
    {
        return $this->setBoolean(self::ASPECT_RATIO, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::ASPECT_RATIO])]
    public function disableAspectRatio(): self
    {
        return $this->setBoolean(self::ASPECT_RATIO, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::DEFAULT_ASPECT_RATIOS])]
    public function enableDefaultAspectRatios(): self
    {
        return $this->setBoolean(self::DEFAULT_ASPECT_RATIOS, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::DEFAULT_ASPECT_RATIOS])]
    public function disableDefaultAspectRatios(): self
    {
        return $this->setBoolean(self::DEFAULT_ASPECT_RATIOS, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::HEIGHT])]
    public function enableHeight(): self
    {
        return $this->setBoolean(self::HEIGHT, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::HEIGHT])]
    public function disableHeight(): self
    {
        return $this->setBoolean(self::HEIGHT, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::MIN_HEIGHT])]
    public function enableMinHeight(): self
    {
        return $this->setBoolean(self::MIN_HEIGHT, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::MIN_HEIGHT])]
    public function disableMinHeight(): self
    {
        return $this->setBoolean(self::MIN_HEIGHT, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::MIN_WIDTH])]
    public function enableMinWidth(): self
    {
        return $this->setBoolean(self::MIN_WIDTH, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::MIN_WIDTH])]
    public function disableMinWidth(): self
    {
        return $this->setBoolean(self::MIN_WIDTH, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::WIDTH])]
    public function enableWidth(): self
    {
        return $this->setBoolean(self::WIDTH, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::WIDTH])]
    public function disableWidth(): self
    {
        return $this->setBoolean(self::WIDTH, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, AspectRatio::SECTION])]
    public function addAspectRatio(string $slug, string $name, string $ratio): self
    {
        $this->settings->addPreset(new AspectRatio($slug, $name, $ratio));

        return $this;
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, DimensionSize::SECTION])]
    public function addDimensionSize(string $slug, string $name, string $size): self
    {
        $this->settings->addPreset(new DimensionSize($slug, $name, $size));

        return $this;
    }
}
