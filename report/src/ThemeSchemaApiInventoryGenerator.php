<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Report;

final readonly class ThemeSchemaApiInventoryGenerator
{
    private ThemeSchemaInventoryBuilder $inventoryBuilder;

    private ThemeSchemaApiCoverageAnalyzer $coverageAnalyzer;

    private ThemeSchemaApiInventoryMarkdownFormatter $markdownFormatter;

    private ThemeSchemaApiInventoryCsvFormatter $csvFormatter;

    public function __construct(
        ?ThemeSchemaInventoryBuilder $inventoryBuilder = null,
        ?ThemeSchemaApiCoverageAnalyzer $coverageAnalyzer = null,
        ?ThemeSchemaApiInventoryMarkdownFormatter $markdownFormatter = null,
        ?ThemeSchemaApiInventoryCsvFormatter $csvFormatter = null,
    ) {
        $this->inventoryBuilder = $inventoryBuilder ?? new ThemeSchemaInventoryBuilder();
        $this->coverageAnalyzer = $coverageAnalyzer ?? new ThemeSchemaApiCoverageAnalyzer();
        $this->markdownFormatter = $markdownFormatter ?? new ThemeSchemaApiInventoryMarkdownFormatter();
        $this->csvFormatter = $csvFormatter ?? new ThemeSchemaApiInventoryCsvFormatter();
    }

    /**
     * @return array{
     *     markdown_path: string,
     *     csv_path: string,
     *     rows: int,
     *     summary: array{
     *         schema_rows: int,
     *         covered_rows: int,
     *         missing_rows: int,
     *         stale_attributes: int
     *     }
     * }
     */
    public function generate(string $schemaPath, string $outputDirectory): array
    {
        $inventory = $this->inventoryBuilder->build($schemaPath);
        $report = $this->coverageAnalyzer->analyze($inventory);
        $outputDirectory = \rtrim($outputDirectory, '/');

        if (!\is_dir($outputDirectory) && !\mkdir($outputDirectory, 0777, true) && !\is_dir($outputDirectory)) {
            throw new \RuntimeException(\sprintf('Unable to create output directory %s.', $outputDirectory));
        }

        $markdownPath = $outputDirectory . '/theme-schema-api-inventory.md';
        $csvPath = $outputDirectory . '/theme-schema-api-inventory.csv';

        $this->writeFile($markdownPath, $this->markdownFormatter->format($report));
        $this->writeFile($csvPath, $this->csvFormatter->format($report));

        return [
            'markdown_path' => $markdownPath,
            'csv_path' => $csvPath,
            'rows' => \count($report['rows']),
            'summary' => $report['summary'],
        ];
    }

    private function writeFile(string $path, string $contents): void
    {
        if (\file_put_contents($path, $contents) === false) {
            throw new \RuntimeException(\sprintf('Unable to write report file %s.', $path));
        }
    }
}
