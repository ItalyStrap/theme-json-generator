<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Settings;
use ItalyStrap\ThemeJsonGenerator\Settings\Border\RadiusSize;

final readonly class Border
{
    public const COLOR = 'color';

    public const RADIUS = 'radius';

    public const STYLE = 'style';

    public const WIDTH = 'width';

    public const RADIUS_SIZES = 'radiusSizes';

    public function __construct(
        private Settings $settings,
    ) {
    }

    public function enableColor(): self
    {
        $this->set(self::COLOR, true);
        return $this;
    }

    public function disableColor(): self
    {
        $this->set(self::COLOR, false);
        return $this;
    }

    public function enableRadius(): self
    {
        $this->set(self::RADIUS, true);
        return $this;
    }

    public function disableRadius(): self
    {
        $this->set(self::RADIUS, false);
        return $this;
    }

    public function enableStyle(): self
    {
        $this->set(self::STYLE, true);
        return $this;
    }

    public function disableStyle(): self
    {
        $this->set(self::STYLE, false);
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

    public function addRadiusSize(string $slug, string $name, string $size): self
    {
        $this->settings->addPreset(['border', self::RADIUS_SIZES], new RadiusSize($slug, $name, $size));

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

        return $this->settings->set(['border', ...$path], $value);
    }
}
