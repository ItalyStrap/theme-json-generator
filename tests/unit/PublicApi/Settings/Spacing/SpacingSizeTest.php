<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Spacing;

use ItalyStrap\Tests\Unit\PublicApi\Settings\PresetCommonTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Spacing\SpacingSize;

final class SpacingSizeTest extends UnitTestCase
{
    use PresetCommonTrait;

    private string $slug = '50';

    private function makeInstance(): SpacingSize
    {
        return new SpacingSize($this->slug, 'Medium', '1rem');
    }

    public function testItShouldCreateCorrectArray(): void
    {
        $this->assertSame([
            'slug' => '50',
            'name' => 'Medium',
            'size' => '1rem',
        ], $this->makeInstance()->toArray());
    }

    public function testItShouldCreateSpacingCustomProperty(): void
    {
        $this->assertSame('--wp--preset--spacing--50', $this->makeInstance()->prop());
    }
}
