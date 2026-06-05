<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

final readonly class CustomTemplates
{
    public function __construct(
        private ThemeJson $themeJson,
    ) {
    }

    /**
     * @param list<string> $postTypes
     */
    public function addTemplate(string $name, string $title, array $postTypes = []): self
    {
        if ($name === '') {
            throw new \InvalidArgumentException('Expected a non-empty custom template name.');
        }

        if ($title === '') {
            throw new \InvalidArgumentException('Expected a non-empty custom template title.');
        }

        foreach ($postTypes as $postType) {
            if ($postType === '') {
                throw new \InvalidArgumentException('Expected custom template post types to contain non-empty values.');
            }
        }

        $customTemplate = \array_filter([
            'name' => $name,
            'title' => $title,
            'postTypes' => $postTypes,
        ], static fn (string|array $value): bool => $value !== []);

        if (!$this->themeJson->appendTo('customTemplates', [$customTemplate])) {
            throw new \RuntimeException('Unable to append root property "customTemplates".');
        }

        return $this;
    }
}
