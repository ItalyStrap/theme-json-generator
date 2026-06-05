<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

final readonly class Patterns
{
    public function __construct(
        private ThemeJson $themeJson,
    ) {
    }

    public function add(string $pattern): self
    {
        if (!$this->themeJson->appendTo('patterns', $pattern)) {
            throw new \RuntimeException('Unable to append root property "patterns".');
        }

        return $this;
    }
}
