<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings;

final readonly class Position
{
    use ScopedSettingsWriterTrait;

    public const SECTION = 'position';

    public const STICKY = 'sticky';

    public function __construct(
        private Settings $settings,
    ) {
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::STICKY])]
    public function enableSticky(): self
    {
        return $this->setBoolean(self::STICKY, true);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::STICKY])]
    public function disableSticky(): self
    {
        return $this->setBoolean(self::STICKY, false);
    }
}
