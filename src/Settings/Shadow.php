<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Shadow as ShadowPreset;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\BoxShadow;

final readonly class Shadow
{
    use ScopedSettingsWriterTrait;

    private const SECTION = 'shadow';

    public const DEFAULT_PRESETS = 'defaultPresets';

    public const PRESETS = 'presets';

    public function __construct(
        private Settings $settings,
    ) {
    }

    #[ThemeSchemaCoverage(['settings', 'shadow', 'defaultPresets'])]
    public function enableDefaultPresets(): self
    {
        $this->set(self::DEFAULT_PRESETS, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'shadow', 'defaultPresets'])]
    public function disableDefaultPresets(): self
    {
        $this->set(self::DEFAULT_PRESETS, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'shadow', 'presets'])]
    public function addShadow(string $slug, string $name, BoxShadow ...$shadow): self
    {
        $this->settings->addPreset(new ShadowPreset($slug, $name, ...$shadow));

        return $this;
    }
}
