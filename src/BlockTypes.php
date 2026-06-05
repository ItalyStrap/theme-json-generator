<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;

final readonly class BlockTypes
{
    public function __construct(
        private ThemeJson $themeJson,
    ) {
    }

    #[ThemeSchemaCoverage(['topLevel', 'blockTypes'])]
    public function add(string $blockType): self
    {
        if (!$this->themeJson->appendTo('blockTypes', $blockType)) {
            throw new \RuntimeException('Unable to append root property "blockTypes".');
        }

        return $this;
    }
}
