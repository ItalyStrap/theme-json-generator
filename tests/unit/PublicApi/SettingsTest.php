<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class SettingsTest extends UnitTestCase
{
    public function testItShouldRejectNestedBlocks(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(
            'Cannot chain "blocks()" after "blocks()": this settings structure is not supported.'
        );

        $this->makeThemeJson()->settings()->blocks('core/group')->blocks('core/paragraph');
    }

    /**
     * @dataProvider invalidBlockNameProvider
     */
    public function testItShouldRejectInvalidBlockNames(string $block): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a valid block name');

        $this->makeThemeJson()->settings()->blocks($block);
    }

    public static function invalidBlockNameProvider(): iterable
    {
        yield 'empty' => [''];
        yield 'missing namespace' => ['paragraph'];
        yield 'uppercase' => ['core/Paragraph'];
        yield 'nested path' => ['core/group/color'];
    }

    public function testDeprecatedBlockSettingsBridgeStillWorks(): void
    {
        $sut = $this->makeThemeJson();

        $this->assertTrue($sut->setBlockSettings('core/paragraph', ['color' => ['text' => true]]));
        $this->assertTrue($sut->get('settings.blocks.core/paragraph.color.text'));
    }

    private function makeThemeJson(): ThemeJson
    {
        return new ThemeJson(new Config(), new Presets());
    }
}
