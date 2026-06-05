<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

final readonly class BlockTypes
{
    public function __construct(
        private ThemeJson $themeJson,
    ) {
    }

    public function add(string $blockType): self
    {
        if (!$this->themeJson->appendTo('blockTypes', $blockType)) {
            throw new \RuntimeException('Unable to append root property "blockTypes".');
        }

        return $this;
    }
}
