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

    private const SECTION = 'dimensions';

    public const ASPECT_RATIO = 'aspectRatio';

    public const ASPECT_RATIOS = 'aspectRatios';

    public const DEFAULT_ASPECT_RATIOS = 'defaultAspectRatios';

    public const DIMENSION_SIZES = 'dimensionSizes';

    public const HEIGHT = 'height';

    public const MIN_HEIGHT = 'minHeight';

    public const MIN_WIDTH = 'minWidth';

    public const WIDTH = 'width';

    public function __construct(
        private Settings $settings,
    ) {
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'aspectRatio'])]
    public function enableAspectRatio(): self
    {
        return $this->setBoolean(self::ASPECT_RATIO, true);
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'aspectRatio'])]
    public function disableAspectRatio(): self
    {
        return $this->setBoolean(self::ASPECT_RATIO, false);
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'defaultAspectRatios'])]
    public function enableDefaultAspectRatios(): self
    {
        return $this->setBoolean(self::DEFAULT_ASPECT_RATIOS, true);
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'defaultAspectRatios'])]
    public function disableDefaultAspectRatios(): self
    {
        return $this->setBoolean(self::DEFAULT_ASPECT_RATIOS, false);
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'height'])]
    public function enableHeight(): self
    {
        return $this->setBoolean(self::HEIGHT, true);
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'height'])]
    public function disableHeight(): self
    {
        return $this->setBoolean(self::HEIGHT, false);
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'minHeight'])]
    public function enableMinHeight(): self
    {
        return $this->setBoolean(self::MIN_HEIGHT, true);
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'minHeight'])]
    public function disableMinHeight(): self
    {
        return $this->setBoolean(self::MIN_HEIGHT, false);
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'minWidth'])]
    public function enableMinWidth(): self
    {
        return $this->setBoolean(self::MIN_WIDTH, true);
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'minWidth'])]
    public function disableMinWidth(): self
    {
        return $this->setBoolean(self::MIN_WIDTH, false);
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'width'])]
    public function enableWidth(): self
    {
        return $this->setBoolean(self::WIDTH, true);
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'width'])]
    public function disableWidth(): self
    {
        return $this->setBoolean(self::WIDTH, false);
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'aspectRatios'])]
    public function addAspectRatio(string $slug, string $name, string $ratio): self
    {
        $this->settings->addPreset(new AspectRatio($slug, $name, $ratio));

        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'dimensionSizes'])]
    public function addDimensionSize(string $slug, string $name, string $size): self
    {
        $this->settings->addPreset(new DimensionSize($slug, $name, $size));

        return $this;
    }
}
