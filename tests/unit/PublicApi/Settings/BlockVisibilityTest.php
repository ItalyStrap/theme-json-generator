<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\BlockVisibility;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class BlockVisibilityTest extends UnitTestCase
{
    public function testItShouldToggleEditingWithoutAddingAnEnabledProperty(): void
    {
        $themeJson = new ThemeJson(new Config(), new Presets());
        $visibility = $themeJson->settings()->blockVisibility();

        $this->assertInstanceOf(BlockVisibility::class, $visibility);
        $this->assertNull($themeJson->get('settings.blockVisibility'));
        $this->assertSame($visibility, $visibility->enableEditing());
        $this->assertSame(['allowEditing' => true], $themeJson->get('settings.blockVisibility'));
        $this->assertSame($visibility, $visibility->disableEditing());
        $this->assertSame(['allowEditing' => false], $themeJson->get('settings.blockVisibility'));
    }

    public function testItShouldRejectBlockScopedSettings(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The blockVisibility section is only supported at the root settings level.');

        (new ThemeJson(new Config(), new Presets()))->settings()->blocks('core/group')->blockVisibility();
    }
}
