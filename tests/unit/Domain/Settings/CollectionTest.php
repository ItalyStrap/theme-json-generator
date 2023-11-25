<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Settings;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Collection;

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
        $category = 'color';

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

        $this->assertIsArray($sut->toArrayByCategory('color'));
        $this->assertSame(
            [
                [
                    'slug' => $slug,
                    'name' => $name,
                    'color' => $color,
                ]
            ],
            $sut->toArrayByCategory('color'),
            ''
        );
    }
}
