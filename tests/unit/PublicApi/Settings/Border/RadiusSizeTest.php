<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Border;

use ItalyStrap\Tests\Unit\PublicApi\Settings\PresetCommonTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Border\RadiusSize;

final class RadiusSizeTest extends UnitTestCase
{
    use PresetCommonTrait;

    private string $slug = 'small';

    private string $name = 'Small';

    private string $size = '4px';

    private function makeInstance(): RadiusSize
    {
        return new RadiusSize($this->slug, $this->name, $this->size);
    }

    public function testItShouldCreateCorrectArray(): void
    {
        $this->assertSame([
            'slug' => 'small',
            'name' => 'Small',
            'size' => '4px',
        ], $this->makeInstance()->toArray());
    }

    public function testItShouldCreateBorderRadiusCustomProperty(): void
    {
        $this->assertSame('--wp--preset--border-radius--small', $this->makeInstance()->prop());
    }
}
