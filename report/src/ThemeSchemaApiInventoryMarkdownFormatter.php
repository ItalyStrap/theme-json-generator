<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Report;

final class ThemeSchemaApiInventoryMarkdownFormatter
{
    /**
     * @param array{
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
     * } $report
     */
    public function format(array $report): string
    {
        $lines = [
            '# Theme Schema API Inventory',
            '',
            '## Summary',
            '',
            '| Metric | Count |',
            '| --- | ---: |',
            \sprintf('| schema rows | %d |', $report['summary']['schema_rows']),
            \sprintf('| covered rows | %d |', $report['summary']['covered_rows']),
            \sprintf('| missing rows | %d |', $report['summary']['missing_rows']),
            \sprintf('| stale attributes | %d |', $report['summary']['stale_attributes']),
            '',
            '## Category Summary',
            '',
            '| Category | Schema rows | Covered rows | Missing rows |',
            '| --- | ---: | ---: | ---: |',
        ];

        foreach ($report['category_summary'] as $category) {
            $lines[] = \sprintf(
                '| %s | %d | %d | %d |',
                $this->markdownCell($this->categoryTitle($category['category'])),
                $category['schema_rows'],
                $category['covered_rows'],
                $category['missing_rows']
            );
        }

        $lines[] = '';
        $lines[] = '## Attribute Coverage Not In Schema';
        $lines[] = '';

        if ($report['stale_attributes'] === []) {
            $lines[] = 'No stale attributes.';
        } else {
            $lines[] = '| Path | API |';
            $lines[] = '| --- | --- |';
        }

        foreach ($report['stale_attributes'] as $attribute) {
            $lines[] = \sprintf(
                '| `%s` | %s |',
                $this->markdownCell($attribute['path']),
                $this->markdownCell(\implode('<br>', $attribute['supported_by']))
            );
        }

        foreach ($this->groupRowsByCategory($report['rows']) as $category => $rows) {
            $lines[] = '';
            $lines[] = \sprintf('## %s', $this->categoryTitle($category));
            $lines[] = '';
            $lines[] = '| Path | API | Description |';
            $lines[] = '| --- | --- | --- |';

            foreach ($rows as $row) {
                $lines[] = \sprintf(
                    '| `%s` | %s | %s |',
                    $this->markdownCell($row['path']),
                    $this->markdownCell(\implode('<br>', $row['supported_by'])),
                    $this->markdownCell($row['description'] ?? '')
                );
            }
        }

        $lines[] = '';

        return \implode("\n", $lines);
    }

    /**
     * @param list<array{
     *     category: string,
     *     path: string,
     *     name: string,
     *     description: string|null,
     *     supported_by: list<string>
     * }> $rows
     * @return array<string, list<array{
     *     category: string,
     *     path: string,
     *     name: string,
     *     description: string|null,
     *     supported_by: list<string>
     * }>>
     */
    private function groupRowsByCategory(array $rows): array
    {
        $grouped = [];

        foreach ($rows as $row) {
            $grouped[$row['category']][] = $row;
        }

        return $grouped;
    }

    private function categoryTitle(string $category): string
    {
        return match ($category) {
            'topLevel' => 'Top-level properties',
            'settings' => 'Settings',
            'settings.blocks' => 'Settings blocks',
            'styles' => 'Styles',
            'styles.elements' => 'Styles elements',
            'styles.blocks' => 'Styles blocks',
            default => $category,
        };
    }

    private function markdownCell(string $value): string
    {
        $value = \str_replace('|', '\|', $value);
        return \str_replace("\n", '<br>', $value);
    }
}
