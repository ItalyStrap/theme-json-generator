<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;

final readonly class TemplateParts
{
    /**
     * @var string
     */
    public const SECTION = 'templateParts';

    public function __construct(
        private ThemeJson $themeJson,
    ) {
    }

    #[ThemeSchemaCoverage(['topLevel', 'templateParts'])]
    public function addPart(string $name, string $area = 'uncategorized', ?string $title = null): self
    {
        if ($name === '') {
            throw new \InvalidArgumentException('Expected a non-empty template part name.');
        }

        if ($area === '') {
            throw new \InvalidArgumentException('Expected a non-empty template part area.');
        }

        $templatePart = \array_filter([
            'name' => $name,
            'title' => $title,
            'area' => $area,
        ], static fn (?string $value): bool => $value !== null);

        $this->themeJson->appendTo('templateParts', [$templatePart]);
        return $this;
    }
}
