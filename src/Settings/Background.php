<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Settings;
use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;

final readonly class Background
{
    public const BACKGROUND_IMAGE = 'backgroundImage';

    public const BACKGROUND_SIZE = 'backgroundSize';

    public const GRADIENT = 'gradient';

    public function __construct(
        private Settings $settings,
    ) {
    }

    #[ThemeSchemaCoverage(['settings', 'background', 'backgroundImage'])]
    public function enableBackgroundImage(): self
    {
        $this->set(self::BACKGROUND_IMAGE, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'background', 'backgroundImage'])]
    public function disableBackgroundImage(): self
    {
        $this->set(self::BACKGROUND_IMAGE, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'background', 'backgroundSize'])]
    public function enableBackgroundSize(): self
    {
        $this->set(self::BACKGROUND_SIZE, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'background', 'backgroundSize'])]
    public function disableBackgroundSize(): self
    {
        $this->set(self::BACKGROUND_SIZE, false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'background', 'gradient'])]
    public function enableGradient(): self
    {
        $this->set(self::GRADIENT, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'background', 'gradient'])]
    public function disableGradient(): self
    {
        $this->set(self::GRADIENT, false);
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

        return $this->settings->set(['background', ...$path], $value);
    }
}
