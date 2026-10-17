<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings;

final readonly class Viewport
{
    use ScopedSettingsWriterTrait;

    public const SECTION = 'viewport';

    public const MOBILE = 'mobile';

    public const TABLET = 'tablet';

    public function __construct(private Settings $settings)
    {
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::MOBILE])]
    public function mobile(string $width): self
    {
        return $this->breakpoint(self::MOBILE, $width);
    }

    #[ThemeSchemaCoverage([Settings::SECTION, self::SECTION, self::TABLET])]
    public function tablet(string $width): self
    {
        return $this->breakpoint(self::TABLET, $width);
    }

    private function breakpoint(string $property, string $width): self
    {
        if (\preg_match('/^(?:\d+|\d*\.\d+)(?:px|em|rem)$/D', $width) !== 1) {
            throw new \InvalidArgumentException('Viewport width must be a non-negative length in px, em, or rem.');
        }

        $this->set($property, $width);
        return $this;
    }
}
