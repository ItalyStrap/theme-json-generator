<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Application\Config;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Application\Config\Blueprint;

class BlueprintTest extends UnitTestCase
{
    private function makeInstance(): Blueprint
    {
        return new Blueprint();
    }

    public function testItShouldImplementsJsonSerializable(): void
    {
        $sut = $this->makeInstance();
        $this->assertInstanceOf(\JsonSerializable::class, $sut, 'Should implements JsonSerializable');
    }

    public function testItShouldBeJsonSerializable(): void
    {
        $sut = $this->makeInstance();
        $sut->merge([
            'foo' => 'bar',
            'baz' => 'qux',
        ]);

        $this->assertJsonStringEqualsJsonString(
            '{"foo":"bar","baz":"qux"}',
            \json_encode($sut),
            'Json encode should be equals'
        );
    }
}
