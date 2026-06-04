<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Lightbox;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class LightboxTest extends UnitTestCase
{
    public function testItShouldWriteLightboxSettingsInsideBlockContext(): void
    {
        $sut = new ThemeJson(new Config(), new Presets());

        $result = $sut->settings()->blocks('core/image')->lightbox()
            ->enable()
            ->disableEditing();

        $this->assertInstanceOf(Lightbox::class, $result);
        $this->assertTrue($sut->get('settings.blocks.core/image.lightbox.enabled'));
        $this->assertFalse($sut->get('settings.blocks.core/image.lightbox.allowEditing'));
    }
}
