<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Settings\Color;

use ItalyStrap\Tests\Unit\Domain\Settings\CommonTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Palette;

class PaletteTest extends UnitTestCase
{
    use CommonTrait;

    private string $slug = 'base';

    private string $name = 'Base';

    private function makeInstance(): Palette
    {
        return new Palette(
            $this->slug,
            $this->name,
            $this->makeColorInfo()
        );
    }

    public function testItShouldReturnTheName(): void
    {
        $this->colorInfo
            ->__toString()
            ->willReturn('#ffffff');

        $sut = $this->makeInstance();
        $this->assertSame($this->name, $sut->toArray()['name']);
    }

    public function testItShouldReturnTheColor(): void
    {
        $this->colorInfo
            ->__toString()
            ->willReturn('#ffffff');

        $sut = $this->makeInstance();
        $this->assertSame('#ffffff', $sut->toArray()['color']);
    }

    public function testItShouldReturnRef(): void
    {
        $sut = $this->makeInstance();
        $this->assertSame('{{color.base}}', $sut->ref());
    }

    public function testItShouldReturnProp(): void
    {
        $sut = $this->makeInstance();
        $this->assertSame('--wp--preset--color--base', $sut->prop());
    }

    public function testItShouldReturnVar(): void
    {
        $sut = $this->makeInstance();
        $this->assertSame('var(--wp--preset--color--base)', $sut->var());
    }

    public static function propertiesProvider(): \Generator
    {
        yield 'Primary color' => [
            '--wp--preset--color--primary',
            'primary',
            '#ffffff',
        ];

        yield 'Secondary color' => [
            '--wp--preset--color--secondary',
            'secondary',
            '#000000',
        ];

        yield 'Foo color' => [
            '--wp--preset--color--foo',
            'Foo',
            '#000000',
        ];

        yield 'FooBar color' => [
            '--wp--preset--color--foo-bar',
            'FooBar',
            '#000000',
        ];

        yield 'Foo123 color' => [
            '--wp--preset--color--foo-123',
            'Foo123',
            '#000000',
        ];

        yield 'Foo123Bar color' => [
            '--wp--preset--color--foo-123-bar',
            'Foo123Bar',
            '#000000',
        ];

        yield 'foo color' => [
            '--wp--preset--color--foo',
            'foo',
            '#000000',
        ];

        yield 'fooBar color' => [
            '--wp--preset--color--foo-bar',
            'fooBar',
            '#000000',
        ];

        yield 'foo123 color' => [
            '--wp--preset--color--foo-123',
            'foo123',
            '#000000',
        ];

        yield '123Foo color' => [
            '--wp--preset--color--123-foo',
            '123Foo',
            '#000000',
        ];

        yield 'fooBar123 color' => [
            '--wp--preset--color--foo-bar-123',
            'fooBar123',
            '#000000',
        ];

        yield 'foo123Bar color' => [
            '--wp--preset--color--foo-123-bar',
            'foo123Bar',
            '#000000',
        ];

        yield '123FooBar color' => [
            '--wp--preset--color--123-foo-bar',
            '123FooBar',
            '#000000',
        ];
    }

    /**
     * @dataProvider propertiesProvider
     * @test
     */
    public function itShouldReturnCssPropertyFor(string $expected, string $prop, string $value)
    {
        $this->slug = $prop;
        $this->name = $prop;
        $this->colorInfo
            ->__toString()
            ->willReturn($value);

        $sut = $this->makeInstance();

        $this->assertStringMatchesFormat(
            $expected,
            $sut->prop(),
            ''
        );
    }
}
