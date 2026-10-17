<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Dimensions;

use ItalyStrap\Tests\Unit\PublicApi\Settings\PresetCommonTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Dimensions\AspectRatio;

final class AspectRatioTest extends UnitTestCase
{
    use PresetCommonTrait;

    private string $slug = 'square';

    private function makeInstance(): AspectRatio
    {
        return new AspectRatio($this->slug, 'Square', '1/1');
    }

    public function testItShouldCreateCorrectArray(): void
    {
        $this->assertSame([
            'slug' => 'square',
            'name' => 'Square',
            'ratio' => '1/1',
        ], $this->makeInstance()->toArray());
    }

    public function testItShouldCreateAspectRatioCustomProperty(): void
    {
        $this->assertSame('--wp--preset--aspect-ratio--square', $this->makeInstance()->prop());

        $newAspectRatio = new AspectRatio('4-3', '4:3', '4/3');
        $this->assertSame('--wp--preset--aspect-ratio--4-3', $newAspectRatio->prop());
    }
}
