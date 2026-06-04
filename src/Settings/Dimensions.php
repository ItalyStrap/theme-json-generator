<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Settings;
use ItalyStrap\ThemeJsonGenerator\Settings\Dimensions\AspectRatio;
use ItalyStrap\ThemeJsonGenerator\Settings\Dimensions\DimensionSize;

final readonly class Dimensions
{
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

    public function enableAspectRatio(): self
    {
        $this->set(self::ASPECT_RATIO, true);
        return $this;
    }

    public function disableAspectRatio(): self
    {
        $this->set(self::ASPECT_RATIO, false);
        return $this;
    }

    public function enableDefaultAspectRatios(): self
    {
        $this->set(self::DEFAULT_ASPECT_RATIOS, true);
        return $this;
    }

    public function disableDefaultAspectRatios(): self
    {
        $this->set(self::DEFAULT_ASPECT_RATIOS, false);
        return $this;
    }

    public function enableHeight(): self
    {
        $this->set(self::HEIGHT, true);
        return $this;
    }

    public function disableHeight(): self
    {
        $this->set(self::HEIGHT, false);
        return $this;
    }

    public function enableMinHeight(): self
    {
        $this->set(self::MIN_HEIGHT, true);
        return $this;
    }

    public function disableMinHeight(): self
    {
        $this->set(self::MIN_HEIGHT, false);
        return $this;
    }

    public function enableMinWidth(): self
    {
        $this->set(self::MIN_WIDTH, true);
        return $this;
    }

    public function disableMinWidth(): self
    {
        $this->set(self::MIN_WIDTH, false);
        return $this;
    }

    public function enableWidth(): self
    {
        $this->set(self::WIDTH, true);
        return $this;
    }

    public function disableWidth(): self
    {
        $this->set(self::WIDTH, false);
        return $this;
    }

    public function addAspectRatio(string $slug, string $name, string $ratio): self
    {
        $this->settings->addPreset(
            ['dimensions', self::ASPECT_RATIOS],
            new AspectRatio($slug, $name, $ratio)
        );

        return $this;
    }

    public function addDimensionSize(string $slug, string $name, string $size): self
    {
        $this->settings->addPreset(
            ['dimensions', self::DIMENSION_SIZES],
            new DimensionSize($slug, $name, $size)
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

        return $this->settings->set(['dimensions', ...$path], $value);
    }
}
