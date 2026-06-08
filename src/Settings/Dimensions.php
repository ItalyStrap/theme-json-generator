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
        $this->set(self::ASPECT_RATIO, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'aspectRatio'])]
    public function disableAspectRatio(): self
    {
        $this->set(self::ASPECT_RATIO, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'defaultAspectRatios'])]
    public function enableDefaultAspectRatios(): self
    {
        $this->set(self::DEFAULT_ASPECT_RATIOS, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'defaultAspectRatios'])]
    public function disableDefaultAspectRatios(): self
    {
        $this->set(self::DEFAULT_ASPECT_RATIOS, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'height'])]
    public function enableHeight(): self
    {
        $this->set(self::HEIGHT, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'height'])]
    public function disableHeight(): self
    {
        $this->set(self::HEIGHT, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'minHeight'])]
    public function enableMinHeight(): self
    {
        $this->set(self::MIN_HEIGHT, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'minHeight'])]
    public function disableMinHeight(): self
    {
        $this->set(self::MIN_HEIGHT, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'minWidth'])]
    public function enableMinWidth(): self
    {
        $this->set(self::MIN_WIDTH, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'minWidth'])]
    public function disableMinWidth(): self
    {
        $this->set(self::MIN_WIDTH, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'width'])]
    public function enableWidth(): self
    {
        $this->set(self::WIDTH, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions', 'width'])]
    public function disableWidth(): self
    {
        $this->set(self::WIDTH, false);
        return $this;
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
