<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings;

final readonly class Background
{
    use ScopedSettingsWriterTrait;

    private const SECTION = 'background';

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
        return $this->setBoolean(self::BACKGROUND_IMAGE, true);
    }

    #[ThemeSchemaCoverage(['settings', 'background', 'backgroundImage'])]
    public function disableBackgroundImage(): self
    {
        return $this->setBoolean(self::BACKGROUND_IMAGE, false);
    }

    #[ThemeSchemaCoverage(['settings', 'background', 'backgroundSize'])]
    public function enableBackgroundSize(): self
    {
        return $this->setBoolean(self::BACKGROUND_SIZE, true);
    }

    #[ThemeSchemaCoverage(['settings', 'background', 'backgroundSize'])]
    public function disableBackgroundSize(): self
    {
        return $this->setBoolean(self::BACKGROUND_SIZE, false);
    }

    #[ThemeSchemaCoverage(['settings', 'background', 'gradient'])]
    public function enableGradient(): self
    {
        return $this->setBoolean(self::GRADIENT, true);
    }

    #[ThemeSchemaCoverage(['settings', 'background', 'gradient'])]
    public function disableGradient(): self
    {
        return $this->setBoolean(self::GRADIENT, false);
    }
}
