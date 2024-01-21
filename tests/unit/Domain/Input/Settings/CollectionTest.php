<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Settings;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Collection;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Palette;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\ItemInterface;

class CollectionTest extends UnitTestCase
{
    private function makeInstance(): Collection
    {
        return new Collection();
    }

    private function prepareFakeItem(string $val = ''): ItemInterface
    {
        return new class ($val) implements ItemInterface {
            private string $val;

            public function __construct(string $val = '')
            {
                $this->val = $val;
            }

            public function slug(): string
            {
                return 'slug' . $this->val;
            }

            public function ref(): string
            {
                return 'ref' . $this->val;
            }

            public function prop(): string
            {
                return 'prop' . $this->val;
            }

            public function var(string $fallback = ''): string
            {
                return $fallback !== '' ? $fallback : 'var' . $this->val;
            }

            public function __toString(): string
            {
                return $this->var();
            }

            public function toArray(): array
            {
                return [];
            }

            public function category(): string
            {
                return 'category' . $this->val;
            }
        };
    }

    public function testItShouldGet(): void
    {
        $sut = $this->makeInstance();
        $sut->addMultiple([$this->prepareFakeItem('1')]);

        $this->assertInstanceOf(ItemInterface::class, $sut->get('category1.slug1'));
        $this->assertSame('var1', (string)$sut->get('category1.slug1'));
        $this->assertNull($sut->get('category1.slug2'));
        $this->assertSame('default', $sut->get('category1.slug2', 'default'));
    }

    public function testItShouldParse(): void
    {
        $sut = $this->makeInstance();
        $sut->addMultiple([
            $this->prepareFakeItem('1'),
            $this->prepareFakeItem('2'),
        ]);

        $this->assertSame('var1', $sut->parse('{{category1.slug1}}'));
        $this->assertSame('var1var2', $sut->parse('{{category1.slug1}}{{category2.slug2}}'));

        $this->assertSame('passThrough', $sut->parse('passThrough'));
    }

    public function testItShouldReturnTheCollection(): void
    {
        $slug = 'base';
        $name = 'Base';
        $color = '#ffffff';
        $category = Palette::CATEGORY;

        $this->item
            ->category()
            ->willReturn($category);

        $this->item
            ->slug()
            ->willReturn($slug);

        $this->item
            ->toArray()
            ->willReturn([
                'slug' => $slug,
                'name' => $name,
                'color' => $color,
            ]);

        $sut = $this->makeInstance();
        $sut->add($this->makeItem());

        $this->assertIsArray($sut->toArrayByCategory(Palette::CATEGORY), '');
        $this->assertSame(
            [
                [
                    'slug' => $slug,
                    'name' => $name,
                    'color' => $color,
                ]
            ],
            $sut->toArrayByCategory(Palette::CATEGORY),
            ''
        );
    }

    public function testItShouldBeJsonSerializableByTag(): void
    {
        $slug = 'base';
        $name = 'Base';
        $color = '#ffffff';

        $this->item
            ->category()
            ->willReturn(Palette::CATEGORY);

        $this->item
            ->slug()
            ->willReturn($slug);

        $this->item
            ->toArray()
            ->willReturn([
                'slug' => $slug,
                'name' => $name,
                'color' => $color,
            ]);

        $sut = $this->makeInstance();
        $sut->add($this->makeItem());

        $sut->field(Palette::CATEGORY);

        $this->assertJsonStringEqualsJsonString(
            '[{"slug":"base","name":"Base","color":"#ffffff"}]',
            \json_encode($sut),
            ''
        );
    }

    public static function noPlaceholderProvider(): iterable
    {
        yield 'empty' => [
            'actual' => '',
            'expected' => '',
        ];

        yield 'no placeholder' => [
            'actual' => 'foo',
            'expected' => 'foo',
        ];
    }

    /**
     * @dataProvider noPlaceholderProvider
     */
    public function testParseShouldReturnEarly(
        string $actual,
        string $expected
    ): void {
        $sut = $this->makeInstance();
        $this->assertSame($expected, $sut->parse($actual));
    }
}
