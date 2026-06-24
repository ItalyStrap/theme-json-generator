<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Typography;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography;

final readonly class Fluid
{
    public const SECTION = 'fluid';

    public const MAX_VIEWPORT_WIDTH = 'maxViewportWidth';

    public const MIN_FONT_SIZE = 'minFontSize';

    public const MIN_VIEWPORT_WIDTH = 'minViewportWidth';

    public function __construct(private Typography $typography)
    {
    }

    #[ThemeSchemaCoverage([Settings::SECTION, Typography::SECTION, self::SECTION])]
    public function minFontSize(string $minFontSize): self
    {
        return $this->set(self::MIN_FONT_SIZE, $minFontSize);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, Typography::SECTION, self::SECTION])]
    public function maxViewportWidth(string $maxViewportWidth): self
    {
        return $this->set(self::MAX_VIEWPORT_WIDTH, $maxViewportWidth);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, Typography::SECTION, self::SECTION])]
    public function minViewportWidth(string $minViewportWidth): self
    {
        return $this->set(self::MIN_VIEWPORT_WIDTH, $minViewportWidth);
    }

    private function set(string $property, string $value): self
    {
        $this->typography->writeFluidConfig($property, $value);
        return $this;
    }
}
