<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Palette;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class ColorTest extends UnitTestCase
{
    public function testItShouldWriteColorSettingsInsideBlockContext(): void
    {
        $sut = new ThemeJson(new Config(), new Presets());

        $result = $sut->settings()->blocks('core/group')->color()
            ->enableText()
            ->disableBackground()
            ->enableLink()
            ->disableCustom()
            ->enableCustomDuotone()
            ->disableCustomGradient()
            ->enableDefaultDuotone()
            ->disableDefaultGradients()
            ->enableDefaultPalette()
            ->disableHeading()
            ->enableButton()
            ->disableCaption();

        $this->assertInstanceOf(Color::class, $result);
        $this->assertTrue($sut->get('settings.blocks.core/group.color.text'));
        $this->assertFalse($sut->get('settings.blocks.core/group.color.background'));
        $this->assertTrue($sut->get('settings.blocks.core/group.color.link'));
        $this->assertFalse($sut->get('settings.blocks.core/group.color.custom'));
        $this->assertTrue($sut->get('settings.blocks.core/group.color.customDuotone'));
        $this->assertFalse($sut->get('settings.blocks.core/group.color.customGradient'));
        $this->assertTrue($sut->get('settings.blocks.core/group.color.defaultDuotone'));
        $this->assertFalse($sut->get('settings.blocks.core/group.color.defaultGradients'));
        $this->assertTrue($sut->get('settings.blocks.core/group.color.defaultPalette'));
        $this->assertFalse($sut->get('settings.blocks.core/group.color.heading'));
        $this->assertTrue($sut->get('settings.blocks.core/group.color.button'));
        $this->assertFalse($sut->get('settings.blocks.core/group.color.caption'));
    }

    public function testItShouldRejectInvalidPaletteItems(): void
    {
        $sut = new ThemeJson(new Config(), new Presets());

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected an instance of ' . Palette::class . ', got string.');

        $sut->settings()->color()->addColors(['#ffffff']);
    }
}
