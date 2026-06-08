<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings;
use ItalyStrap\ThemeJsonGenerator\Settings\Border\RadiusSize;

final readonly class Border
{
    use ScopedSettingsWriterTrait;

    private const SECTION = 'border';

    public const COLOR = 'color';

    public const RADIUS = 'radius';

    public const STYLE = 'style';

    public const WIDTH = 'width';

    public const RADIUS_SIZES = 'radiusSizes';

    public function __construct(
        private Settings $settings,
    ) {
    }

    #[ThemeSchemaCoverage(['settings', 'border', 'color'])]
    public function enableColor(): self
    {
        $this->set(self::COLOR, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'border', 'color'])]
    public function disableColor(): self
    {
        $this->set(self::COLOR, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'border', 'radius'])]
    public function enableRadius(): self
    {
        $this->set(self::RADIUS, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'border', 'radius'])]
    public function disableRadius(): self
    {
        $this->set(self::RADIUS, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'border', 'style'])]
    public function enableStyle(): self
    {
        $this->set(self::STYLE, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'border', 'style'])]
    public function disableStyle(): self
    {
        $this->set(self::STYLE, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'border', 'width'])]
    public function enableWidth(): self
    {
        $this->set(self::WIDTH, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'border', 'width'])]
    public function disableWidth(): self
    {
        $this->set(self::WIDTH, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'border', 'radiusSizes'])]
    public function addRadiusSize(string $slug, string $name, string $size): self
    {
        $this->settings->addPreset(new RadiusSize($slug, $name, $size));

        return $this;
    }
}
