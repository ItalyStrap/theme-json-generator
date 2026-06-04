<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Dimensions;

use ItalyStrap\Tests\Unit\PublicApi\Settings\PresetCommonTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Dimensions\DimensionSize;

final class DimensionSizeTest extends UnitTestCase
{
    use PresetCommonTrait;

    private string $slug = 'content';

    private function makeInstance(): DimensionSize
    {
        return new DimensionSize($this->slug, 'Content', '42rem');
    }

    public function testItShouldCreateCorrectArray(): void
    {
        $this->assertSame([
            'slug' => 'content',
            'name' => 'Content',
            'size' => '42rem',
        ], $this->makeInstance()->toArray());
    }

    public function testItShouldCreateDimensionCustomProperty(): void
    {
        $this->assertSame('--wp--preset--dimension--content', $this->makeInstance()->prop());
    }
}
