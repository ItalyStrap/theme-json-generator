<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Container\PresetsToThemeJson;
use ItalyStrap\ThemeJsonGenerator\Settings\Dimensions;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class DimensionsTest extends UnitTestCase
{
    public function testItShouldWriteDimensionsSettingsInsideBlockContext(): void
    {
        $presets = new Presets();
        $config = new Config();
        $sut = new ThemeJson($config, $presets);

        $result = $sut->settings()->blocks('core/group')->dimensions()
            ->enableAspectRatio()
            ->disableDefaultAspectRatios()
            ->enableHeight()
            ->disableMinHeight()
            ->enableMinWidth()
            ->disableWidth()
            ->addAspectRatio('square', 'Square', '1/1')
            ->addDimensionSize('content', 'Content', '42rem');

        $this->assertInstanceOf(Dimensions::class, $result);
        $this->assertTrue($sut->get('settings.blocks.core/group.dimensions.aspectRatio'));
        $this->assertFalse($sut->get('settings.blocks.core/group.dimensions.defaultAspectRatios'));
        $this->assertTrue($sut->get('settings.blocks.core/group.dimensions.height'));
        $this->assertFalse($sut->get('settings.blocks.core/group.dimensions.minHeight'));
        $this->assertTrue($sut->get('settings.blocks.core/group.dimensions.minWidth'));
        $this->assertFalse($sut->get('settings.blocks.core/group.dimensions.width'));

        (new PresetsToThemeJson())($config, $presets);

        $this->assertSame(
            [
                [
                    'slug' => 'square',
                    'name' => 'Square',
                    'ratio' => '1/1',
                ],
            ],
            $sut->get('settings.blocks.core/group.dimensions.aspectRatios')
        );
        $this->assertSame(
            [
                [
                    'slug' => 'content',
                    'name' => 'Content',
                    'size' => '42rem',
                ],
            ],
            $sut->get('settings.blocks.core/group.dimensions.dimensionSizes')
        );
    }
}
