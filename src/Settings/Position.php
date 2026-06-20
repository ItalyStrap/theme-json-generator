<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings;

final readonly class Position
{
    use ScopedSettingsWriterTrait;

    private const SECTION = 'position';

    public const STICKY = 'sticky';

    public function __construct(
        private Settings $settings,
    ) {
    }

    #[ThemeSchemaCoverage(['settings', 'position', 'sticky'])]
    public function enableSticky(): self
    {
        return $this->setBoolean(self::STICKY, true);
    }

    #[ThemeSchemaCoverage(['settings', 'position', 'sticky'])]
    public function disableSticky(): self
    {
        return $this->setBoolean(self::STICKY, false);
    }
}
