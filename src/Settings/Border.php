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
        return $this->setBoolean(self::COLOR, true);
    }

    #[ThemeSchemaCoverage(['settings', 'border', 'color'])]
    public function disableColor(): self
    {
        return $this->setBoolean(self::COLOR, false);
    }

    #[ThemeSchemaCoverage(['settings', 'border', 'radius'])]
    public function enableRadius(): self
    {
        return $this->setBoolean(self::RADIUS, true);
    }

    #[ThemeSchemaCoverage(['settings', 'border', 'radius'])]
    public function disableRadius(): self
    {
        return $this->setBoolean(self::RADIUS, false);
    }

    #[ThemeSchemaCoverage(['settings', 'border', 'style'])]
    public function enableStyle(): self
    {
        return $this->setBoolean(self::STYLE, true);
    }

    #[ThemeSchemaCoverage(['settings', 'border', 'style'])]
    public function disableStyle(): self
    {
        return $this->setBoolean(self::STYLE, false);
    }

    #[ThemeSchemaCoverage(['settings', 'border', 'width'])]
    public function enableWidth(): self
    {
        return $this->setBoolean(self::WIDTH, true);
    }

    #[ThemeSchemaCoverage(['settings', 'border', 'width'])]
    public function disableWidth(): self
    {
        return $this->setBoolean(self::WIDTH, false);
    }

    #[ThemeSchemaCoverage(['settings', 'border', 'radiusSizes'])]
    public function addRadiusSize(string $slug, string $name, string $size): self
    {
        $this->settings->addPreset(new RadiusSize($slug, $name, $size));

        return $this;
    }
}
