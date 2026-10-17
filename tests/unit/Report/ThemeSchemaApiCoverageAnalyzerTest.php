<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Report;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Report\AttributeThemeSchemaApiCoverageProvider;
use ItalyStrap\ThemeJsonGenerator\Report\ThemeSchemaApiCoverageAnalyzer;
use ItalyStrap\ThemeJsonGenerator\Report\ThemeSchemaApiCoverageProvider;
use ItalyStrap\ThemeJsonGenerator\Report\ThemeSchemaInventoryBuilder;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class ThemeSchemaApiCoverageAnalyzerTest extends UnitTestCase
{
    public function testItMapsInventoryRowsToCoverageSupport(): void
    {
        $inventory = (new ThemeSchemaInventoryBuilder())->build(\codecept_root_dir('theme.schema.json'));
        $report = (new ThemeSchemaApiCoverageAnalyzer())->analyze($inventory);

        $byPath = [];
        foreach ($report['rows'] as $row) {
            $byPath[$row['path']] = $row;
        }

        $this->assertContains('ThemeJson::version()', $byPath['topLevel.version']['supported_by']);
        $this->assertContains(
            'Settings\\Color::disableBackground()',
            $byPath['settings.color.background']['supported_by']
        );
        $this->assertContains('Styles::border()', $byPath['styles.border']['supported_by']);
        $this->assertContains('Settings::blocks()', $byPath['settings.blocks.*']['supported_by']);
        $this->assertContains('Styles::blocks()', $byPath['styles.blocks.*']['supported_by']);
        $this->assertSame(0, $report['summary']['missing_rows']);
        $this->assertSame(0, $report['summary']['stale_attributes']);
    }

    public function testItCollectsCoverageFromAttributes(): void
    {
        $coverage = (new AttributeThemeSchemaApiCoverageProvider())->all();

        $this->assertArrayHasKey('topLevel.version', $coverage);
        $this->assertArrayHasKey('supported_by', $coverage['topLevel.version']);
    }

    public function testItReportsStaleAttributesNotFoundInTheSchema(): void
    {
        $inventory = [
            'schema_path' => 'theme.schema.json',
            'rows' => [
                [
                    'category' => 'topLevel',
                    'path' => 'topLevel.version',
                    'name' => 'version',
                    'description' => null,
                ],
            ],
        ];
        $provider = new class implements ThemeSchemaApiCoverageProvider {
            public function all(): array
            {
                return [
                    'topLevel.version' => [
                        'supported_by' => [[ThemeJson::class, 'version']],
                    ],
                    'topLevel.removed' => [
                        'supported_by' => [[ThemeJson::class, 'title']],
                    ],
                ];
            }
        };

        $report = (new ThemeSchemaApiCoverageAnalyzer($provider))->analyze($inventory);

        $this->assertSame(1, $report['summary']['stale_attributes']);
        $this->assertSame('topLevel.removed', $report['stale_attributes'][0]['path']);
        $this->assertContains('ThemeJson::title()', $report['stale_attributes'][0]['supported_by']);
    }
}
