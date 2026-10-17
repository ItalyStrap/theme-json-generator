<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Report;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Report\ThemeSchemaApiInventoryGenerator;

final class ThemeSchemaApiInventoryGeneratorTest extends UnitTestCase
{
    public function testItWritesMarkdownAndCsvReports(): void
    {
        $sut = new ThemeSchemaApiInventoryGenerator();
        $outputDirectory = \codecept_output_dir((string) random_int(0, \mt_getrandmax()));

        $result = $sut->generate(\codecept_root_dir('theme.schema.json'), $outputDirectory);

        $this->assertFileExists($result['markdown_path']);
        $this->assertFileExists($result['csv_path']);
        $this->assertStringContainsString(
            '| Path | API | Description |',
            (string) \file_get_contents($result['markdown_path'])
        );
        $this->assertStringContainsString(
            '## Category Summary',
            (string) \file_get_contents($result['markdown_path'])
        );
        $this->assertStringContainsString(
            'kind,category,path,name,api,description',
            (string) \file_get_contents($result['csv_path'])
        );

        \unlink($result['markdown_path']);
        \unlink($result['csv_path']);
        \rmdir($outputDirectory);
    }
}
