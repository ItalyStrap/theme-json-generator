<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Typography;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography;

final readonly class Fluid
{
    public function __construct(private Typography $typography)
    {
    }

    #[ThemeSchemaCoverage(['settings', 'typography', 'fluid'])]
    public function minFontSize(string $minFontSize): self
    {
        return $this->set('minFontSize', $minFontSize);
    }

    #[ThemeSchemaCoverage(['settings', 'typography', 'fluid'])]
    public function maxViewportWidth(string $maxViewportWidth): self
    {
        return $this->set('maxViewportWidth', $maxViewportWidth);
    }

    #[ThemeSchemaCoverage(['settings', 'typography', 'fluid'])]
    public function minViewportWidth(string $minViewportWidth): self
    {
        return $this->set('minViewportWidth', $minViewportWidth);
    }

    private function set(string $property, string $value): self
    {
        $this->typography->set([Typography::FLUID, $property], $value);
        return $this;
    }
}
