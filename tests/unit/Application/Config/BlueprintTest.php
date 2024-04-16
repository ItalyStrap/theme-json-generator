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

    public function testSetGlobalCss(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->setGlobalCss('foo'), 'Should return true');
        $this->assertSame('foo', $sut->get('styles.css'), 'Should be equals');
    }

    public function testAppendGlobalCss(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->setGlobalCss('foo'), 'Should return true');
        $this->assertTrue($sut->appendGlobalCss('bar'), 'Should return true');
        $this->assertSame('foobar', $sut->get('styles.css'), 'Should be equals');
    }

    public function testSetElementStyle(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->setElementStyle('foo', ['bar' => 'baz']), 'Should return true');
        $this->assertSame(['bar' => 'baz'], $sut->get('styles.elements.foo'), 'Should be equals');
    }

    public function testSetBlockSettings(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->setBlockSettings('foo', ['bar' => 'baz']), 'Should return true');
        $this->assertSame(['bar' => 'baz'], $sut->get('settings.blocks.foo'), 'Should be equals');
    }

    public function testSetBlockStyle(): void
    {
        $sut = $this->makeInstance();
        $this->assertTrue($sut->setBlockStyle('foo', ['bar' => 'baz']), 'Should return true');
        $this->assertSame(['bar' => 'baz'], $sut->get('styles.blocks.foo'), 'Should be equals');
    }
}
