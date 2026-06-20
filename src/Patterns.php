<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;

final readonly class Patterns
{
    /**
     * @var string
     */
    public const SECTION = 'patterns';

    public function __construct(
        private ThemeJson $themeJson,
    ) {
    }

    #[ThemeSchemaCoverage(['topLevel', 'patterns'])]
    public function add(string $pattern): self
    {
        if ($pattern === '') {
            throw new \InvalidArgumentException('Expected a non-empty pattern name.');
        }

        $this->themeJson->appendTo('patterns', $pattern);
        return $this;
    }
}
