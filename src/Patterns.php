<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;

final readonly class Patterns
{
    public function __construct(
        private ThemeJson $themeJson,
    ) {
    }

    #[ThemeSchemaCoverage(['topLevel', 'patterns'])]
    public function add(string $pattern): self
    {
        if (!$this->themeJson->appendTo('patterns', $pattern)) {
            throw new \RuntimeException('Unable to append root property "patterns".');
        }

        return $this;
    }
}
