<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings;

final readonly class BlockVisibility
{
    use ScopedSettingsWriterTrait;

    public const SECTION = 'blockVisibility';

    public const ALLOW_EDITING = 'allowEditing';

    public function __construct(private Settings $settings)
    {
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
