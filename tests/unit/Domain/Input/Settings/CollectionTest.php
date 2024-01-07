<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Settings;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Collection;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Palette;

class CollectionTest extends UnitTestCase
{
    protected function makeInstance(): Collection
    {
        return new Collection();
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
}
