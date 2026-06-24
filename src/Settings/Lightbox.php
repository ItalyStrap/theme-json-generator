<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings;

final readonly class Lightbox
{
    use ScopedSettingsWriterTrait;

    public const SECTION = 'lightbox';

    public const ALLOW_EDITING = 'allowEditing';

    public const ENABLED = 'enabled';

    public function __construct(
        private Settings $settings,
    ) {
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::ENABLED])]
    public function enable(): self
    {
        return $this->setBoolean(self::ENABLED, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::ENABLED])]
    public function disable(): self
    {
        return $this->setBoolean(self::ENABLED, false);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::ALLOW_EDITING])]
    public function enableEditing(): self
    {
        return $this->setBoolean(self::ALLOW_EDITING, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::ALLOW_EDITING])]
    public function disableEditing(): self
    {
        return $this->setBoolean(self::ALLOW_EDITING, false);
    }
}
