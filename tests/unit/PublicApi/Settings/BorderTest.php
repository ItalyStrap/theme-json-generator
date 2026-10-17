<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Border;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class BorderTest extends UnitTestCase
{
    public function testItShouldWriteBorderSettingsInsideBlockContext(): void
    {
        $sut = new ThemeJson(new Config(), new Presets());

        $result = $sut->settings()
            ->blocks('core/group')
            ->border()
            ->disableColor()
            ->disableRadius()
            ->enableStyle()
            ->disableWidth();

        $this->assertInstanceOf(Border::class, $result);
        $this->assertFalse($sut->get('settings.blocks.core/group.border.color'));
        $this->assertFalse($sut->get('settings.blocks.core/group.border.radius'));
        $this->assertTrue($sut->get('settings.blocks.core/group.border.style'));
        $this->assertFalse($sut->get('settings.blocks.core/group.border.width'));
    }
}
