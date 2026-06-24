<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings;
use ItalyStrap\ThemeJsonGenerator\Settings\Border\RadiusSize;

final readonly class Border
{
    use ScopedSettingsWriterTrait;

    public const SECTION = 'border';

    public const COLOR = 'color';

    public const RADIUS = 'radius';

    public const STYLE = 'style';

    public const WIDTH = 'width';

    public function __construct(
        private Settings $settings,
    ) {
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::COLOR])]
    public function enableColor(): self
    {
        return $this->setBoolean(self::COLOR, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::COLOR])]
    public function disableColor(): self
    {
        return $this->setBoolean(self::COLOR, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::RADIUS])]
    public function enableRadius(): self
    {
        return $this->setBoolean(self::RADIUS, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::RADIUS])]
    public function disableRadius(): self
    {
        return $this->setBoolean(self::RADIUS, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::STYLE])]
    public function enableStyle(): self
    {
        return $this->setBoolean(self::STYLE, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::STYLE])]
    public function disableStyle(): self
    {
        return $this->setBoolean(self::STYLE, false);
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

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, RadiusSize::SECTION])]
    public function addRadiusSize(string $slug, string $name, string $size): self
    {
        $this->settings->addPreset(new RadiusSize($slug, $name, $size));

        return $this;
    }
}
