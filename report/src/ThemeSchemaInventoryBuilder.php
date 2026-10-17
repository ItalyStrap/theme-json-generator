<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Report;

final readonly class ThemeSchemaInventoryBuilder
{
    /**
     * @return array{
     *     schema_path: string,
     *     rows: list<array{
     *         category: string,
     *         path: string,
     *         name: string,
     *         description: string|null
     *     }>
     * }
     */
    public function build(string $schemaPath): array
    {
        $schema = $this->decodeSchema($schemaPath);

        return [
            'schema_path' => $schemaPath,
            'rows' => [
                ...$this->buildRootRows($schema),
                ...$this->buildRowsFromDefinitionReferences(
                    $schema,
                    'settingsProperties',
                    'settings'
                ),
                ...$this->buildRowsFromInlineProperties($schema, 'settings'),
                ...$this->buildRowsFromDefinitionProperties(
                    $schema,
                    'settingsBlocksPropertiesComplete',
                    'settings.blocks'
                ),
                ...$this->buildRowsFromDefinitionProperties($schema, 'stylesProperties', 'styles'),
                ...$this->buildRowsFromInlineProperties($schema, 'styles'),
                ...$this->buildRowsFromDefinitionProperties(
                    $schema,
                    'stylesElementsPropertiesComplete',
                    'styles.elements'
                ),
                ...$this->buildRowsFromDefinitionProperties(
                    $schema,
                    'stylesBlocksPropertiesComplete',
                    'styles.blocks'
                ),
            ],
        ];
    }

    /**
     * @param array<string, mixed> $schema
     * @return list<array{category: string, path: string, name: string, description: string|null}>
     */
    private function buildRootRows(array $schema): array
    {
        $rows = [];

        foreach ($this->propertiesOf($schema) as $name => $property) {
            $rows[] = $this->makeRow(
                'topLevel',
                \sprintf('topLevel.%s', $name),
                $name,
                $this->resolveDescription($schema, $property)
            );
        }

        return $rows;
    }

    /**
     * @param array<string, mixed> $schema
     * @return list<array{category: string, path: string, name: string, description: string|null}>
     */
    private function buildRowsFromInlineProperties(array $schema, string $category): array
    {
        $rows = [];
        $properties = $this->propertiesOf($schema);
        $property = $properties[$category] ?? null;

        if (!\is_array($property)) {
            return $rows;
        }

        foreach ($this->allOfItems($property) as $item) {
            foreach ($this->propertiesOf($item) as $name => $nestedProperty) {
                $rows[] = $this->makeRow(
                    $category,
                    \sprintf('%s.%s', $category, $name),
                    $name,
                    $this->resolveDescription($schema, $nestedProperty)
                );
            }
        }

        return $rows;
    }

    /**
     * @param array<string, mixed> $schema
     * @return list<array{category: string, path: string, name: string, description: string|null}>
     */
    private function buildRowsFromDefinitionReferences(array $schema, string $definitionName, string $category): array
    {
        $rows = [];
        $definition = $this->getDefinition($schema, $definitionName);

        foreach ($this->allOfItems($definition) as $item) {
            $referencePath = $item['$ref'] ?? null;

            if (!\is_string($referencePath)) {
                continue;
            }

            $referencedDefinition = $this->resolveReference($schema, $referencePath);

            foreach ($this->propertiesOf($referencedDefinition) as $name => $property) {
                $path = \sprintf('%s.%s', $category, $name);
                $rows[] = $this->makeRow(
                    $category,
                    $path,
                    $name,
                    $this->resolveDescription($schema, $property)
                );

                if ($this->shouldExpandAreaChildren($category)) {
                    $rows = [
                        ...$rows,
                        ...$this->buildChildRows($schema, $path, $property),
                    ];
                }
            }
        }

        return $rows;
    }

    /**
     * @param array<string, mixed> $schema
     * @return list<array{category: string, path: string, name: string, description: string|null}>
     */
    private function buildRowsFromDefinitionProperties(array $schema, string $definitionName, string $category): array
    {
        if ($this->shouldCollapseToWildcard($category)) {
            return [
                $this->makeRow(
                    $category,
                    \sprintf('%s.*', $category),
                    '*',
                    null
                ),
            ];
        }

        $rows = [];
        $definition = $this->getDefinition($schema, $definitionName);

        foreach ($this->propertiesOf($definition) as $name => $property) {
            $path = \sprintf('%s.%s', $category, $name);
            $rows[] = $this->makeRow(
                $category,
                $path,
                $name,
                $this->resolveDescription($schema, $property)
            );

            if ($this->shouldExpandAreaChildren($category)) {
                $rows = [
                    ...$rows,
                    ...$this->buildChildRows($schema, $path, $property),
                ];
            }
        }

        return $rows;
    }

    /**
     * @param array<string, mixed> $schema
     * @param array<string, mixed> $property
     */
    private function resolveDescription(array $schema, array $property): ?string
    {
        $description = $property['description'] ?? null;

        if (\is_string($description) && $description !== '') {
            return \trim($description);
        }

        if (isset($property['$ref']) && \is_string($property['$ref'])) {
            $reference = $this->resolveReference($schema, $property['$ref']);
            $referenceDescription = $reference['description'] ?? null;

            if (\is_string($referenceDescription) && $referenceDescription !== '') {
                return \trim($referenceDescription);
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeSchema(string $schemaPath): array
    {
        $json = \file_get_contents($schemaPath);

        if ($json === false) {
            throw new \RuntimeException(\sprintf('Unable to read schema file at %s.', $schemaPath));
        }

        $decoded = \json_decode($json, true);

        if (!\is_array($decoded)) {
            throw new \RuntimeException(\sprintf('Unable to decode schema file at %s.', $schemaPath));
        }

        /** @var array<string, mixed> $decoded */
        return $decoded;
    }

    /**
     * @param array<string, mixed> $schema
     * @return array<string, mixed>
     */
    private function getDefinition(array $schema, string $definitionName): array
    {
        $definition = $this->definitionsOf($schema)[$definitionName] ?? null;

        if (!\is_array($definition)) {
            throw new \RuntimeException(\sprintf('Schema definition %s not found.', $definitionName));
        }

        /** @var array<string, mixed> $definition */
        return $definition;
    }

    /**
     * @param array<string, mixed> $schema
     * @return array<string, mixed>
     */
    private function resolveReference(array $schema, string $reference): array
    {
        $prefix = '#/definitions/';

        if (!\str_starts_with($reference, $prefix)) {
            throw new \RuntimeException(\sprintf('Unsupported schema reference %s.', $reference));
        }

        return $this->getDefinition($schema, \substr($reference, \strlen($prefix)));
    }

    /**
     * @param array<string, mixed> $schema
     * @param array<string, mixed> $property
     * @return list<array{category: string, path: string, name: string, description: string|null}>
     */
    private function buildChildRows(array $schema, string $parentPath, array $property): array
    {
        $rows = [];

        foreach ($this->nestedPropertiesOf($schema, $property) as $name => $childProperty) {
            $path = \sprintf('%s.%s', $parentPath, $name);
            $rows[] = $this->makeRow(
                $parentPath,
                $path,
                $name,
                $this->resolveDescription($schema, $childProperty)
            );
            $rows = [
                ...$rows,
                ...$this->buildChildRows($schema, $path, $childProperty),
            ];
        }

        return $rows;
    }

    private function shouldExpandAreaChildren(string $category): bool
    {
        return \in_array($category, ['settings', 'styles'], true);
    }

    private function shouldCollapseToWildcard(string $category): bool
    {
        return \in_array($category, ['settings.blocks', 'styles.blocks'], true);
    }

    /**
     * @param array<string, mixed> $schema
     * @param array<string, mixed> $node
     * @return array<string, array<string, mixed>>
     */
    private function nestedPropertiesOf(array $schema, array $node): array
    {
        $properties = $this->propertiesOf($node);

        $reference = $node['$ref'] ?? null;
        if (\is_string($reference)) {
            $properties = [
                ...$properties,
                ...$this->nestedPropertiesOf($schema, $this->resolveReference($schema, $reference)),
            ];
        }

        foreach ($this->allOfItems($node) as $item) {
            $itemReference = $item['$ref'] ?? null;

            if (\is_string($itemReference)) {
                $properties = [
                    ...$properties,
                    ...$this->nestedPropertiesOf($schema, $this->resolveReference($schema, $itemReference)),
                ];
                continue;
            }

            $properties = [
                ...$properties,
                ...$this->nestedPropertiesOf($schema, $item),
            ];
        }

        return $properties;
    }

    /**
     * @param array<string, mixed> $node
     * @return array<string, array<string, mixed>>
     */
    private function propertiesOf(array $node): array
    {
        $properties = $node['properties'] ?? null;

        if (!\is_array($properties)) {
            return [];
        }

        $normalized = [];

        foreach ($properties as $name => $property) {
            if (\is_string($name) && \is_array($property)) {
                $normalized[$name] = \array_filter($property, '\is_string', \ARRAY_FILTER_USE_KEY);
            }
        }

        return $normalized;
    }

    /**
     * @param array<string, mixed> $node
     * @return list<array<string, mixed>>
     */
    private function allOfItems(array $node): array
    {
        $allOf = $node['allOf'] ?? null;

        if (!\is_array($allOf)) {
            return [];
        }

        $normalized = [];

        foreach ($allOf as $item) {
            if (\is_array($item)) {
                $normalized[] = \array_filter($item, '\is_string', \ARRAY_FILTER_USE_KEY);
            }
        }

        return $normalized;
    }

    /**
     * @param array<string, mixed> $schema
     * @return array<string, array<string, mixed>>
     */
    private function definitionsOf(array $schema): array
    {
        $definitions = $schema['definitions'] ?? null;

        if (!\is_array($definitions)) {
            return [];
        }

        $normalized = [];

        foreach ($definitions as $name => $definition) {
            if (\is_string($name) && \is_array($definition)) {
                $normalized[$name] = \array_filter($definition, '\is_string', \ARRAY_FILTER_USE_KEY);
            }
        }

        return $normalized;
    }

    /**
     * @return array{category: string, path: string, name: string, description: string|null}
     */
    private function makeRow(
        string $category,
        string $path,
        string $name,
        ?string $description
    ): array {
        return [
            'category' => $category,
            'path' => $path,
            'name' => $name,
            'description' => $description,
        ];
    }
}
