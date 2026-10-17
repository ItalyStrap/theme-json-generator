<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings;
use ItalyStrap\ThemeJsonGenerator\Settings\Shadow\Shadow as ShadowPreset;
use ItalyStrap\ThemeJsonGenerator\Settings\Shadow\Utilities\BoxShadow;

final readonly class Shadow
{
    use ScopedSettingsWriterTrait;

    public const SECTION = 'shadow';

    public const DEFAULT_PRESETS = 'defaultPresets';

    public function __construct(
        private Settings $settings,
    ) {
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::DEFAULT_PRESETS])]
    public function enableDefaultPresets(): self
    {
        return $this->setBoolean(self::DEFAULT_PRESETS, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::DEFAULT_PRESETS])]
    public function disableDefaultPresets(): self
    {
        return $this->setBoolean(self::DEFAULT_PRESETS, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, ShadowPreset::SECTION])]
    public function addShadow(string $slug, string $name, BoxShadow ...$shadow): self
    {
        $this->settings->addPreset(new ShadowPreset($slug, $name, ...$shadow));

        return $this;
    }
}
