<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Settings;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetTrait;

class CommonTest extends UnitTestCase
{
    use PresetTrait;

    public static function invalidSlugProvider()
    {
        yield 'empty' => [''];
        yield 'with space' => ['with space'];
    }

    /**
     * @dataProvider invalidSlugProvider
     */
    public function testSlugIsWellFormed(string $slug): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->assertSlugIsWellFormed($slug);
    }

    public static function camelToUnderscoreProvider(): \Generator
    {
        yield 'base' => [
            'actual' => 'base',
            'expected' => 'base',
        ];

        yield 'baseName' => [
            'actual' => 'baseName',
            'expected' => 'base-name',
        ];

        yield 'foo123' => [
            'actual' => 'foo123',
            'expected' => 'foo-123',
        ];

        yield '123Foo' => [
            'actual' => '123Foo',
            'expected' => '123-foo',
        ];

        yield 'fooBar123' => [
            'actual' => 'fooBar123',
            'expected' => 'foo-bar-123',
        ];

        yield 'foo123Bar' => [
            'actual' => 'foo123Bar',
            'expected' => 'foo-123-bar',
        ];

        yield '123FooBar' => [
            'actual' => '123FooBar',
            'expected' => '123-foo-bar',
        ];

        yield 'fooBARFoo' => [
            'actual' => 'fooBARFoo',
            'expected' => 'foo-barfoo',
        ];

        yield 'foo--bar' => [
            'actual' => 'foo--bar',
            'expected' => 'foo--bar',
        ];

        yield 'foo-bar' => [
            'actual' => 'foo-bar',
            'expected' => 'foo-bar',
        ];

        yield 'foo_bar' => [
            'actual' => 'foo_bar',
            'expected' => 'foo_bar',
        ];

        yield '--wp--preset--color--foo-123-bar' => [
            'actual' => '--wp--preset--Color--foo123bar',
            'expected' => '--wp--preset--color--foo-123-bar',
        ];

        yield '--wp--preset--font-size--h-1' => [
            'actual' => '--wp--preset--FontSize--h1',
            'expected' => '--wp--preset--font-size--h-1',
        ];
    }

    /**
     * @dataProvider camelToUnderscoreProvider
     */
    public function testCamelToSnake(string $actual, string $expected): void
    {
        $this->assertSame($expected, $this->camelToSnake($actual));
    }
}
