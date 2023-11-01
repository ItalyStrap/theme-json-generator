<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Infrastructure\Filesystem;

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

    /**
     * @test
     */
    public function itShouldBeInstantiatable()
    {
        $sut = $this->makeInstance();
    }

    /**
     * @test
     */
    public function itShouldReturnValidJson()
    {
        $sut = $this->makeInstance();
        $expected = '{"key": "value"}';


//      $callable = function ( array $args ): array {
//          return $this->getInputData();
//      };

        $callable = function (string $path): array {
            $this->assertStringContainsString($path, $this->theme_json_path, '');
            return [
                'key'   => 'value',
            ];
        };

        $sut->build($callable);

        $this->assertJsonStringEqualsJsonFile($this->theme_json_path, $expected, '');
    }
}
