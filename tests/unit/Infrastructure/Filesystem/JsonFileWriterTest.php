<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Infrastructure\Filesystem;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\JsonFileWriter;

class JsonFileWriterTest extends UnitTestCase
{
    private string $theme_json_path;

    protected function makeInstance(): JsonFileWriter
    {
        $this->theme_json_path = \codecept_output_dir(random_int(0, mt_getrandmax()) . '/theme.json');
        return new JsonFileWriter($this->theme_json_path);
    }

    public function testItShouldReturnValidJson(): void
    {
        $sut = $this->makeInstance();
        $expected = '{"key": "value"}';

        $data = new Config([
            'key'   => 'value',
        ]);

        $sut->write($data);

        $this->assertJsonStringEqualsJsonFile($this->theme_json_path, $expected, '');
    }
}
