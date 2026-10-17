<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Cli\Infrastructure\Filesystem;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Filesystem\ScssFileWriter;

final class ScssFileWriterTest extends UnitTestCase
{
    public function testItCreatesDirectoriesAndReplacesStaleOutput(): void
    {
        $directory = \codecept_output_dir('scss-' . \bin2hex(\random_bytes(6)));
        $path = $directory . '/_theme.scss';
        $writer = new ScssFileWriter($path);
        $this->assertFalse($writer->isCurrent('new'));
        $writer->write('old');
        $this->assertFalse($writer->isCurrent('new'));
        $writer->write('new');
        $this->assertTrue($writer->isCurrent('new'));
        $this->assertStringEqualsFile($path, 'new');
        \unlink($path);
        \rmdir($directory);
    }

    public function testItRejectsNonScssDestinations(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new ScssFileWriter('theme.json');
    }
}
