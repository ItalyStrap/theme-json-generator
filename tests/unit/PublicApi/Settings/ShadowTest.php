<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Container\PresetsToThemeJson;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\Settings\Shadow;
use ItalyStrap\ThemeJsonGenerator\Settings\Shadow\Utilities\BoxShadow;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class ShadowTest extends UnitTestCase
{
    public function testItShouldWriteShadowSettingsInsideBlockContext(): void
    {
        $presets = new Presets();
        $config = new Config();
        $sut = new ThemeJson($config, $presets);

        $result = $sut->settings()->blocks('core/group')->shadow()
            ->disableDefaultPresets()
            ->addShadow(
                'soft',
                'Soft',
                (new BoxShadow())->offsetX('0')->offsetY('2px')->blur('4px')->color('#000000')
            );

        $this->assertInstanceOf(Shadow::class, $result);
        $this->assertFalse($sut->get('settings.blocks.core/group.shadow.defaultPresets'));

        (new PresetsToThemeJson())($config, $presets);

        $this->assertSame(
            [
                [
                    'slug' => 'soft',
                    'name' => 'Soft',
                    'shadow' => '0 2px 4px #000000',
                ],
            ],
            $sut->get('settings.blocks.core/group.shadow.presets')
        );
    }
}
