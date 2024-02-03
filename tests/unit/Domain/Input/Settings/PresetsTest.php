<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Settings;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Palette;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetInterface;

class PresetsTest extends UnitTestCase
{
    private function makeInstance(): Presets
    {
        return new Presets();
    }

    public function testItShouldGet(): void
    {
        $sut = $this->makeInstance();
        $sut->addMultiple([$this->prepareFakeItem('1')]);

        $this->assertInstanceOf(PresetInterface::class, $sut->get('category1.slug1'));
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
        $this->assertSame('', $sut->parse(''));
    }

    public function testItShouldReturnTheCollection(): void
    {
        $sut = $this->makeInstance();
        $sut->add($this->prepareFakeItem('1'));

        $this->assertIsArray($sut->toArrayByCategory('category1'));

        $this->assertSame(
            [
                [
                    'slug' => 'slug1',
                    'ref' => 'ref1',
                    'prop' => 'prop1',
                    'var' => 'var1',
                ]
            ],
            $sut->toArrayByCategory('category1'),
            ''
        );
    }

    public function testItShouldBeJsonSerializableByTag(): void
    {

        $sut = $this->makeInstance();
        $sut->add($this->prepareFakeItem('1'));

        $sut->field('category1');

        $this->assertJsonStringEqualsJsonString(
            '[{"slug":"slug1","ref":"ref1","prop":"prop1","var":"var1"}]',
            \json_encode($sut),
            ''
        );
    }

    private function prepareFakeItem(string $val = ''): PresetInterface
    {
        return new class ($val) implements PresetInterface {
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
                return [
                    'slug' => $this->slug(),
                    'ref' => $this->ref(),
                    'prop' => $this->prop(),
                    'var' => $this->var(),
                ];
            }

            public function category(): string
            {
                return 'category' . $this->val;
            }
        };
    }
}
