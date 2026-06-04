<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Position;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class PositionTest extends UnitTestCase
{
    public function testItShouldWritePositionSettingsInsideBlockContext(): void
    {
        $sut = new ThemeJson(new Config(), new Presets());

        $result = $sut->settings()->blocks('core/group')->position()->disableSticky();

        $this->assertInstanceOf(Position::class, $result);
        $this->assertFalse($sut->get('settings.blocks.core/group.position.sticky'));
    }
}
