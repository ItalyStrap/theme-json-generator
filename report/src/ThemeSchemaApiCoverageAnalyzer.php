<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Report;

final readonly class ThemeSchemaApiCoverageAnalyzer
{
    public function __construct(
        private ?ThemeSchemaApiCoverageProvider $coverageMap = null,
    ) {
    }

    /**
     * @param array{
     *     schema_path: string,
     *     rows: list<array{
     *         category: string,
     *         path: string,
     *         name: string,
     *         description: string|null
     *     }>
     * } $inventory
     * @return array{
     *     summary: array{
     *         schema_rows: int,
     *         covered_rows: int,
     *         missing_rows: int,
     *         stale_attributes: int
     *     },
     *     category_summary: list<array{
     *         category: string,
     *         schema_rows: int,
     *         covered_rows: int,
     *         missing_rows: int
     *     }>,
     *     rows: list<array{
     *         category: string,
     *         path: string,
     *         name: string,
     *         description: string|null,
     *         supported_by: list<string>
     *     }>,
     *     stale_attributes: list<array{
     *         path: string,
     *         supported_by: list<string>
     *     }>
     * }
     */
    public function analyze(array $inventory): array
    {
        $coverage = $this->coverageMap()->all();
        $rows = [];
        $usedCoveragePaths = [];
        $categorySummary = [];
        $summary = [
            'schema_rows' => 0,
            'covered_rows' => 0,
            'missing_rows' => 0,
            'stale_attributes' => 0,
        ];

        foreach ($this->sortInventoryRows($inventory['rows']) as $row) {
            $entry = $this->resolveCoverageEntry($coverage, $row['path'], $row['category']);
            $coveragePath = $entry['coverage_path'];
            $supportedBy = $this->formatSupportedBy($entry['supported_by']);
            $summary['schema_rows']++;
            $categorySummary[$row['category']] ??= [
                'category' => $row['category'],
                'schema_rows' => 0,
                'covered_rows' => 0,
                'missing_rows' => 0,
            ];
            $categorySummary[$row['category']]['schema_rows']++;

            if ($supportedBy === []) {
                $summary['missing_rows']++;
                $categorySummary[$row['category']]['missing_rows']++;
            } else {
                $summary['covered_rows']++;
                $categorySummary[$row['category']]['covered_rows']++;
            }

            if ($coveragePath !== null) {
                $usedCoveragePaths[$coveragePath] = true;
            }

            $rows[] = [
                'category' => $row['category'],
                'path' => $row['path'],
                'name' => $row['name'],
                'description' => $row['description'],
                'supported_by' => $supportedBy,
            ];
        }

        $staleAttributes = $this->staleAttributes($coverage, $usedCoveragePaths);
        $summary['stale_attributes'] = \count($staleAttributes);

        return [
            'summary' => $summary,
            'category_summary' => \array_values($categorySummary),
            'rows' => $rows,
            'stale_attributes' => $staleAttributes,
        ];
    }

    private function coverageMap(): ThemeSchemaApiCoverageProvider
    {
        return $this->coverageMap ?? new AttributeThemeSchemaApiCoverageProvider();
    }

    /**
     * @param array<string, array{
     *     supported_by: list<array{class-string, string|null}>
     * }> $coverage
     * @return array{
     *     coverage_path: string|null,
     *     supported_by: list<array{class-string, string|null}>
     * }
     */
    private function resolveCoverageEntry(array $coverage, string $path, string $category): array
    {
        if (isset($coverage[$path])) {
            return [
                'coverage_path' => $path,
                'supported_by' => $coverage[$path]['supported_by'],
            ];
        }

        $wildcardPath = \sprintf('%s.*', $category);

        if (isset($coverage[$wildcardPath])) {
            return [
                'coverage_path' => $wildcardPath,
                'supported_by' => $coverage[$wildcardPath]['supported_by'],
            ];
        }

        return [
            'coverage_path' => null,
            'supported_by' => [],
        ];
    }

    /**
     * @param list<array{
     *     category: string,
     *     path: string,
     *     name: string,
     *     description: string|null
     * }> $rows
     * @return list<array{
     *     category: string,
     *     path: string,
     *     name: string,
     *     description: string|null
     * }>
     */
    private function sortInventoryRows(array $rows): array
    {
        \usort(
            $rows,
            fn (array $left, array $right): int => [
                $this->categorySortRank($left['category']),
                $left['path'],
            ] <=> [
                $this->categorySortRank($right['category']),
                $right['path'],
            ]
        );

        return $rows;
    }

    private function categorySortRank(string $category): int
    {
        return match (true) {
            $category === 'topLevel' => 0,
            $category === 'settings' => 10,
            $category === 'settings.blocks' => 90,
            \str_starts_with($category, 'settings.') => 20,
            $category === 'styles' => 100,
            $category === 'styles.blocks' => 190,
            \str_starts_with($category, 'styles.') => 110,
            default => 200,
        };
    }

    /**
     * @param list<array{class-string, string|null}> $supportedBy
     * @return list<string>
     */
    private function formatSupportedBy(array $supportedBy): array
    {
        $formatted = [];

        foreach ($supportedBy as [$className, $methodName]) {
            if (!\class_exists($className)) {
                throw new \RuntimeException(\sprintf('Coverage map class %s does not exist.', $className));
            }

            if ($methodName === null) {
                $formatted[] = $this->shortClassName($className);
                continue;
            }

            if (!\method_exists($className, $methodName)) {
                throw new \RuntimeException(\sprintf(
                    'Coverage map method %s::%s does not exist.',
                    $className,
                    $methodName
                ));
            }

            $formatted[] = \sprintf('%s::%s()', $this->shortClassName($className), $methodName);
        }

        $formatted = \array_values(\array_unique($formatted));
        \sort($formatted);

        return $formatted;
    }

    /**
     * @param array<string, array{
     *     supported_by: list<array{class-string, string|null}>
     * }> $coverage
     * @param array<string, true> $usedCoveragePaths
     * @return list<array{
     *     path: string,
     *     supported_by: list<string>
     * }>
     */
    private function staleAttributes(array $coverage, array $usedCoveragePaths): array
    {
        $staleAttributes = [];
        $coveragePaths = \array_keys($coverage);
        \sort($coveragePaths);

        foreach ($coveragePaths as $coveragePath) {
            if (isset($usedCoveragePaths[$coveragePath])) {
                continue;
            }

            $staleAttributes[] = [
                'path' => $coveragePath,
                'supported_by' => $this->formatSupportedBy($coverage[$coveragePath]['supported_by']),
            ];
        }

        return $staleAttributes;
    }

    private function shortClassName(string $className): string
    {
        $prefix = 'ItalyStrap\\ThemeJsonGenerator\\';

        if (\str_starts_with($className, $prefix)) {
            return \substr($className, \strlen($prefix));
        }

        return $className;
    }
}
