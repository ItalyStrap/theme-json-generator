<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Typography;

use ItalyStrap\Tests\Unit\PublicApi\Settings\PresetCommonTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\TextShadow;

final class TextShadowTest extends UnitTestCase
{
    use PresetCommonTrait;

    private string $slug = 'soft';

    private string $name = 'Soft';

    private function makeInstance(): TextShadow
    {
        return new TextShadow($this->slug, $this->name, '0 2px 4px #000000');
    }

    public function testItShouldSerializeTheSchemaFieldsAndCreateTheCorrectVariable(): void
    {
        $shadow = $this->makeInstance();

        $this->assertSame([
            'slug' => 'soft',
            'name' => 'Soft',
            'textShadow' => '0 2px 4px #000000',
        ], $shadow->toArray());
        $this->assertSame('textShadow', $shadow->type());
        $this->assertSame('var(--wp--preset--text-shadow--soft)', $shadow->var());
    }

    public function testItShouldRejectInvalidPresetSlugs(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new TextShadow('soft shadow', 'Soft', '0 2px 4px #000000');
    }
}
