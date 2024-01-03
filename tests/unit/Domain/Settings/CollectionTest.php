<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Settings;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Collection;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Gradient;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Palette;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities\GradientInterface;

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
