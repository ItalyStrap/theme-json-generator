<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Layout;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class LayoutTest extends UnitTestCase
{
    public function testItShouldWriteLayoutSettingsInsideBlockContext(): void
    {
        $sut = new ThemeJson(new Config(), new Presets());

        $result = $sut->settings()->blocks('core/group')->layout()
            ->contentSize('42rem')
            ->wideSize('64rem')
            ->disableEditing()
            ->enableCustomContentAndWideSize();

        $this->assertInstanceOf(Layout::class, $result);
        $this->assertSame('42rem', $sut->get('settings.blocks.core/group.layout.contentSize'));
        $this->assertSame('64rem', $sut->get('settings.blocks.core/group.layout.wideSize'));
        $this->assertFalse($sut->get('settings.blocks.core/group.layout.allowEditing'));
        $this->assertTrue($sut->get('settings.blocks.core/group.layout.allowCustomContentAndWideSize'));
    }

    public function testItShouldWriteOppositeLayoutBooleanValues(): void
    {
        $sut = new ThemeJson(new Config(), new Presets());

        $result = $sut->settings()->layout()
            ->enableEditing()
            ->disableCustomContentAndWideSize();

        $this->assertInstanceOf(Layout::class, $result);
        $this->assertTrue($sut->get('settings.layout.allowEditing'));
        $this->assertFalse($sut->get('settings.layout.allowCustomContentAndWideSize'));
    }
}
