<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Settings;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Shadow as ShadowPreset;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\BoxShadow;

final readonly class Shadow
{
    public const DEFAULT_PRESETS = 'defaultPresets';

    public const PRESETS = 'presets';

    public function __construct(
        private Settings $settings,
    ) {
    }

    public function enableDefaultPresets(): self
    {
        $this->set(self::DEFAULT_PRESETS, true);
        return $this;
    }

    public function disableDefaultPresets(): self
    {
        $this->set(self::DEFAULT_PRESETS, false);
        return $this;
    }

    public function addShadow(string $slug, string $name, BoxShadow ...$shadow): self
    {
        $this->settings->addPreset(['shadow', self::PRESETS], new ShadowPreset($slug, $name, ...$shadow));

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

        return $this->settings->set(['shadow', ...$path], $value);
    }
}
