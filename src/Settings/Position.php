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
        $this->set(self::STICKY, true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'position', 'sticky'])]
    public function disableSticky(): self
    {
        $this->set(self::STICKY, false);
        return $this;
    }
}
