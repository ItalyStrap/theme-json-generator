<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color;

use ItalyStrap\Tests\Unit\PublicApi\Settings\PresetCommonTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Palette;

final class PaletteTest extends UnitTestCase
{
    use PresetCommonTrait;

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

    /**
     * @dataProvider validSlugProvider
     */
    public function testItShouldAcceptValidSlugAtConstruction(string $slug): void
    {
        $sut = new Palette($slug, 'Name', $this->makeColorInfo());

        $this->assertSame($slug, $sut->slug());
    }

    public static function validSlugProvider(): iterable
    {
        yield 'letters' => ['base'];
        yield 'letters and digits' => ['h1'];
        yield 'hyphenated' => ['brand-500'];
        yield 'digits' => ['50'];
        yield 'uppercase letters' => ['BrandBase'];
    }

    /**
     * @dataProvider invalidSlugProvider
     */
    public function testItShouldRejectInvalidSlugAtConstruction(
        string $slug,
        string $expectedMessage
    ): void {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedMessage);

        new Palette($slug, 'Name', $this->makeColorInfo());
    }

    public static function invalidSlugProvider(): iterable
    {
        yield 'empty' => [
            'slug' => '',
            'expectedMessage' => 'Preset slug must not be empty.',
        ];

        foreach (['with space', 'a.b', 'with_underscore', 'path/segment', 'brand@500', 'caffè'] as $slug) {
            yield $slug => [
                'slug' => $slug,
                'expectedMessage' => \sprintf(
                    'Invalid preset slug "%s": only ASCII letters, digits, and hyphens are allowed.',
                    $slug
                ),
            ];
        }
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
     */
    public function testItShouldReturnCssPropertyFor(string $expected, string $prop, string $value): void
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
