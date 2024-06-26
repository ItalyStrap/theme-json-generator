<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Infrastructure\Filesystem;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Application\Config\Blueprint;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\JsonFileWriter;

class JsonFileWriterTest extends UnitTestCase
{
    private string $theme_json_path;

    protected function makeInstance(): JsonFileWriter
    {
        $this->theme_json_path = \codecept_output_dir(random_int(0, mt_getrandmax()) . '/theme.json');
        \mkdir(\dirname($this->theme_json_path), 0777, true);
        return new JsonFileWriter($this->theme_json_path);
    }

    public function testItShouldReturnValidJson(): void
    {
        $sut = $this->makeInstance();
        $expected = '{"key": "value"}';

        $data = new Blueprint([
            'key' => 'value',
        ]);

        $sut->write($data);

        $this->assertJsonStringEqualsJsonFile($this->theme_json_path, $expected, '');

        \unlink($this->theme_json_path);
        \rmdir(\dirname($this->theme_json_path));
    }
}
