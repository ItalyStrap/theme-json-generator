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

    #[ThemeSchemaCoverage([ThemeJson::SECTION, self::SECTION])]
    public function add(string $pattern): self
    {
        if ($pattern === '') {
            throw new \InvalidArgumentException('Expected a non-empty pattern name.');
        }

        $this->themeJson->appendTo(self::SECTION, $pattern);
        return $this;
    }
}
