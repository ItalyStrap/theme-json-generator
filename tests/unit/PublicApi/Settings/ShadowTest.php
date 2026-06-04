<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\BoxShadow;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\Settings\Shadow;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class ShadowTest extends UnitTestCase
{
    public function testItShouldWriteShadowSettingsInsideBlockContext(): void
    {
        $presets = new Presets();
        $sut = new ThemeJson(new Config(), $presets);

        $result = $sut->settings()->blocks('core/group')->shadow()
            ->disableDefaultPresets()
            ->addShadow(
                'soft',
                'Soft',
                (new BoxShadow())->offsetX('0')->offsetY('2px')->blur('4px')->color('#000000')
            );

        $this->assertInstanceOf(Shadow::class, $result);
        $this->assertFalse($sut->get('settings.blocks.core/group.shadow.defaultPresets'));
        $this->assertSame(
            [
                [
                    'slug' => 'soft',
                    'name' => 'Soft',
                    'shadow' => '0 2px 4px #000000',
                ],
            ],
            $presets->toArraysByPath()['settings.blocks.core/group.shadow.presets']
        );
    }
}
