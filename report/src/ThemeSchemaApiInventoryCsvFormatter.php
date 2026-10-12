<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Report;

final class ThemeSchemaApiInventoryCsvFormatter
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
        $stream = \fopen('php://temp', 'rb+');

        if ($stream === false) {
            throw new \RuntimeException('Unable to open temporary CSV stream.');
        }

        \fputcsv(
            $stream,
            [
                'kind',
                'category',
                'path',
                'name',
                'api',
                'description',
            ],
            escape: '\\'
        );

        foreach ($report['rows'] as $row) {
            \fputcsv(
                $stream,
                [
                    'schema',
                    $row['category'],
                    $row['path'],
                    $row['name'],
                    \implode('; ', $row['supported_by']),
                    $row['description'] ?? '',
                ],
                escape: '\\'
            );
        }

        foreach ($report['stale_attributes'] as $attribute) {
            \fputcsv(
                $stream,
                [
                    'attribute_not_in_schema',
                    '',
                    $attribute['path'],
                    '',
                    \implode('; ', $attribute['supported_by']),
                    '',
                ],
                escape: '\\'
            );
        }

        \rewind($stream);
        $csv = \stream_get_contents($stream);
        \fclose($stream);

        if (!\is_string($csv)) {
            throw new \RuntimeException('Unable to read generated CSV output.');
        }

        return $csv;
    }
}
