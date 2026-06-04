<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Background;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class BackgroundTest extends UnitTestCase
{
    public function testItShouldWriteBackgroundSettingsInsideBlockContext(): void
    {
        $sut = new ThemeJson(new Config(), new Presets());

        $result = $sut->settings()
            ->blocks('core/cover')
            ->background()
            ->enableBackgroundImage()
            ->disableBackgroundSize()
            ->enableGradient();

        $this->assertInstanceOf(Background::class, $result);
        $this->assertTrue($sut->get('settings.blocks.core/cover.background.backgroundImage'));
        $this->assertFalse($sut->get('settings.blocks.core/cover.background.backgroundSize'));
        $this->assertTrue($sut->get('settings.blocks.core/cover.background.gradient'));
    }
}
