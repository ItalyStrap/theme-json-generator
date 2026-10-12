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
    public function testItShouldSupportAllSubsetAndDisabledBackgroundClipModes(): void
    {
        $themeJson = new ThemeJson(new Config(), new Presets());
        $background = $themeJson->settings()->background();

        $this->assertSame($background, $background->enableBackgroundClip());
        $this->assertTrue($themeJson->get('settings.background.backgroundClip'));
        $background->backgroundClip('text', 'padding-box', 'border-box', 'content-box');
        $this->assertSame(
            ['text', 'padding-box', 'border-box', 'content-box'],
            $themeJson->get('settings.background.backgroundClip')
        );
        $background->disableBackgroundClip();
        $this->assertFalse($themeJson->get('settings.background.backgroundClip'));
        $background->backgroundClip();
        $this->assertSame([], $themeJson->get('settings.background.backgroundClip'));
        $themeJson->settings()->blocks('core/cover')->background()->backgroundClip('text');
        $this->assertSame(['text'], $themeJson->get('settings.blocks.core/cover.background.backgroundClip'));
        $this->assertSame([], $themeJson->get('settings.background.backgroundClip'));
    }

    /**
     * @dataProvider invalidClipProvider
     * @param list<string> $values
     */
    public function testItShouldRejectInvalidBackgroundClipSubsets(array $values): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new ThemeJson(new Config(), new Presets()))->settings()->background()->backgroundClip(...$values);
    }

    public static function invalidClipProvider(): iterable
    {
        yield 'unknown' => [['invalid']];
        yield 'blank' => [['']];
        yield 'wrong case' => [['TEXT']];
        yield 'duplicate' => [['text', 'text']];
        yield 'invalid second value' => [['border-box', 'invalid']];
    }

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
