<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Container\PresetsToThemeJson;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\Utilities\FontFace;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class TypographyTest extends UnitTestCase
{
    public function testItShouldToggleTextShadowControlsAndSerializeRootAndBlockPresets(): void
    {
        $presets = new Presets();
        $config = new Config();
        $themeJson = new ThemeJson($config, $presets);
        $typography = $themeJson->settings()->typography();

        $this->assertSame($typography, $typography->disableTextShadow()->enableDefaultTextShadowPresets());
        $this->assertFalse($themeJson->get('settings.typography.textShadow'));
        $this->assertTrue($themeJson->get('settings.typography.defaultTextShadowPresets'));
        $typography->enableTextShadow()->disableDefaultTextShadowPresets()
            ->addTextShadow('soft', 'Soft', '0 2px 4px #000000')
            ->addTextShadow('strong', 'Strong', '{{textShadow.soft}}, 0 4px 8px #333333');
        $themeJson->settings()->blocks('core/heading')->typography()
            ->disableTextShadow()->addTextShadow('soft', 'Block Soft', '0 1px 2px #444444');
        $themeJson->styles()->typography()->textShadow('textShadow.soft');
        (new PresetsToThemeJson())($config, $presets);

        $this->assertTrue($themeJson->get('settings.typography.textShadow'));
        $this->assertFalse($themeJson->get('settings.typography.defaultTextShadowPresets'));
        $this->assertSame([
            ['slug' => 'soft', 'name' => 'Soft', 'textShadow' => '0 2px 4px #000000'],
            [
                'slug' => 'strong',
                'name' => 'Strong',
                'textShadow' => 'var(--wp--preset--text-shadow--soft), 0 4px 8px #333333',
            ],
        ], $themeJson->get('settings.typography.textShadowPresets'));
        $this->assertSame([
            ['slug' => 'soft', 'name' => 'Block Soft', 'textShadow' => '0 1px 2px #444444'],
        ], $themeJson->get('settings.blocks.core/heading.typography.textShadowPresets'));
        $this->assertFalse($themeJson->get('settings.blocks.core/heading.typography.textShadow'));
        $this->assertSame('var(--wp--preset--text-shadow--soft)', $themeJson->get('styles.typography.textShadow'));
    }

    public function testItShouldRejectDuplicateTextShadowSlugs(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Preset path textShadow.soft is already registered.');

        (new ThemeJson(new Config(), new Presets()))->settings()->typography()
            ->addTextShadow('soft', 'Soft', '0 2px 4px #000000')
            ->addTextShadow('soft', 'Also Soft', '0 1px 2px #000000');
    }

    public function testItShouldWriteTypographySettingsInsideBlockContext(): void
    {
        $presets = new Presets();
        $config = new Config();
        $sut = new ThemeJson($config, $presets);
        $typography = $sut->settings()->blocks('core/paragraph')->typography();

        $result = $typography
            ->disableDefaultFontSizes()
            ->enableCustomFontSize()
            ->disableFontStyle()
            ->enableFontWeight()
            ->disableLetterSpacing()
            ->enableLineHeight()
            ->textIndent('all')
            ->disableTextAlign()
            ->enableTextColumns()
            ->disableTextDecoration()
            ->enableWritingMode()
            ->disableTextTransform()
            ->enableDropCap()
            ->addFontSize('body', 'Body', '1rem')
            ->addFontFamily(
                'sans',
                'Sans',
                'Arial, sans-serif',
                new FontFace('Arial', '400', 'normal', 'normal', ['file:./arial.woff2'])
            );

        $typography->fluid()
            ->minFontSize('1rem')
            ->maxViewportWidth('80rem')
            ->minViewportWidth('20rem');

        $this->assertInstanceOf(Typography::class, $result);
        $this->assertFalse($sut->get('settings.blocks.core/paragraph.typography.defaultFontSizes'));
        $this->assertTrue($sut->get('settings.blocks.core/paragraph.typography.customFontSize'));
        $this->assertFalse($sut->get('settings.blocks.core/paragraph.typography.fontStyle'));
        $this->assertTrue($sut->get('settings.blocks.core/paragraph.typography.fontWeight'));
        $this->assertFalse($sut->get('settings.blocks.core/paragraph.typography.letterSpacing'));
        $this->assertTrue($sut->get('settings.blocks.core/paragraph.typography.lineHeight'));
        $this->assertSame('all', $sut->get('settings.blocks.core/paragraph.typography.textIndent'));
        $this->assertFalse($sut->get('settings.blocks.core/paragraph.typography.textAlign'));
        $this->assertTrue($sut->get('settings.blocks.core/paragraph.typography.textColumns'));
        $this->assertFalse($sut->get('settings.blocks.core/paragraph.typography.textDecoration'));
        $this->assertTrue($sut->get('settings.blocks.core/paragraph.typography.writingMode'));
        $this->assertFalse($sut->get('settings.blocks.core/paragraph.typography.textTransform'));
        $this->assertTrue($sut->get('settings.blocks.core/paragraph.typography.dropCap'));
        $this->assertSame('1rem', $sut->get('settings.blocks.core/paragraph.typography.fluid.minFontSize'));
        $this->assertSame('80rem', $sut->get('settings.blocks.core/paragraph.typography.fluid.maxViewportWidth'));
        $this->assertSame('20rem', $sut->get('settings.blocks.core/paragraph.typography.fluid.minViewportWidth'));

        (new PresetsToThemeJson())($config, $presets);

        $this->assertSame(
            [
                [
                    'slug' => 'body',
                    'name' => 'Body',
                    'size' => '1rem',
                ],
            ],
            $sut->get('settings.blocks.core/paragraph.typography.fontSizes')
        );
        $this->assertSame(
            [
                [
                    'slug' => 'sans',
                    'name' => 'Sans',
                    'fontFamily' => 'Arial, sans-serif',
                    'fontFace' => [
                        [
                            'fontFamily' => 'Arial',
                            'fontWeight' => '400',
                            'fontStyle' => 'normal',
                            'fontDisplay' => 'fallback',
                            'src' => ['file:./arial.woff2'],
                            'fontStretch' => 'normal',
                        ],
                    ],
                ],
            ],
            $sut->get('settings.blocks.core/paragraph.typography.fontFamilies')
        );
    }

    public function testItShouldWriteBooleanAlternatives(): void
    {
        $sut = new ThemeJson(new Config(), new Presets());

        $result = $sut->settings()->typography()
            ->enableDefaultFontSizes()
            ->disableCustomFontSize()
            ->enableFontStyle()
            ->disableFontWeight()
            ->enableFluid()
            ->disableFluid()
            ->enableLetterSpacing()
            ->disableLineHeight()
            ->disableTextIndent()
            ->enableTextAlign()
            ->disableTextColumns()
            ->enableTextDecoration()
            ->disableWritingMode()
            ->enableTextTransform()
            ->disableDropCap();

        $this->assertInstanceOf(Typography::class, $result);
        $this->assertTrue($sut->get('settings.typography.defaultFontSizes'));
        $this->assertFalse($sut->get('settings.typography.customFontSize'));
        $this->assertTrue($sut->get('settings.typography.fontStyle'));
        $this->assertFalse($sut->get('settings.typography.fontWeight'));
        $this->assertFalse($sut->get('settings.typography.fluid'));
        $this->assertTrue($sut->get('settings.typography.letterSpacing'));
        $this->assertFalse($sut->get('settings.typography.lineHeight'));
        $this->assertFalse($sut->get('settings.typography.textIndent'));
        $this->assertTrue($sut->get('settings.typography.textAlign'));
        $this->assertFalse($sut->get('settings.typography.textColumns'));
        $this->assertTrue($sut->get('settings.typography.textDecoration'));
        $this->assertFalse($sut->get('settings.typography.writingMode'));
        $this->assertTrue($sut->get('settings.typography.textTransform'));
        $this->assertFalse($sut->get('settings.typography.dropCap'));
    }

    public function testItShouldRejectInvalidTextIndent(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected text indent "subsequent" or "all", got "invalid".');

        (new ThemeJson(new Config(), new Presets()))->settings()->typography()->textIndent('invalid');
    }

    public function testItShouldRejectClampFontSizeWhenGlobalFluidIsEnabled(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Fluid typography cannot be applied to a font size that already uses clamp().');

        (new ThemeJson(new Config(), new Presets()))
            ->settings()
            ->typography()
            ->enableFluid()
            ->addFontSize('fluid', 'Fluid', 'clamp(1rem, 2vw, 1.5rem)');
    }

    public function testItShouldRejectClampFontSizeWhenGlobalFluidConfigIsConfigured(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Fluid typography cannot be applied to a font size that already uses clamp().');

        $typography = (new ThemeJson(new Config(), new Presets()))->settings()->typography();

        $typography->fluid()->minFontSize('1rem');
        $typography->addFontSize('fluid', 'Fluid', 'clamp(1rem, 2vw, 1.5rem)');
    }

    public function testItShouldRejectGlobalFluidWhenClampFontSizeIsAlreadyRegistered(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Fluid typography cannot be applied to a font size that already uses clamp().');

        (new ThemeJson(new Config(), new Presets()))
            ->settings()
            ->typography()
            ->addFontSize('fluid', 'Fluid', 'clamp(1rem, 2vw, 1.5rem)')
            ->enableFluid();
    }

    public function testItShouldRejectGlobalFluidConfigWhenClampFontSizeIsAlreadyRegistered(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Fluid typography cannot be applied to a font size that already uses clamp().');

        $typography = (new ThemeJson(new Config(), new Presets()))->settings()->typography();
        $typography->addFontSize('fluid', 'Fluid', 'clamp(1rem, 2vw, 1.5rem)');
        $typography->fluid()->minFontSize('1rem');
    }

    public function testItShouldAllowClampWhenGlobalFluidIsDisabled(): void
    {
        $presets = new Presets();
        $config = new Config();
        $themeJson = new ThemeJson($config, $presets);

        $themeJson->settings()
            ->typography()
            ->disableFluid()
            ->addFontSize('custom-clamp', 'Custom clamp', 'clamp(1rem, 2vw, 1.5rem)');

        (new PresetsToThemeJson())($config, $presets);

        $this->assertSame(
            'clamp(1rem, 2vw, 1.5rem)',
            $themeJson->get('settings.typography.fontSizes.0.size')
        );
    }

    /**
     * @dataProvider booleanFluidMethodProvider
     */
    public function testItShouldRejectReplacingGlobalFluidConfigWithBoolean(string $method): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(
            'Global fluid typography is already configured and cannot be replaced with a boolean.'
        );

        $typography = (new ThemeJson(new Config(), new Presets()))->settings()->typography();
        $typography->fluid()->minFontSize('1rem');
        $typography->{$method}();
    }

    /** @return iterable<string, array{string}> */
    public static function booleanFluidMethodProvider(): iterable
    {
        yield 'enable' => ['enableFluid'];
        yield 'disable' => ['disableFluid'];
    }

    /**
     * @dataProvider booleanFluidMethodProvider
     */
    public function testItShouldRejectExtendingBooleanFluidAsConfig(string $method): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(
            'Global fluid typography is already configured as a boolean and cannot be replaced with an object.'
        );

        $typography = (new ThemeJson(new Config(), new Presets()))->settings()->typography();
        $typography->{$method}();
        $typography->fluid()->minFontSize('1rem');
    }

    public function testFluidAccessorAloneShouldNotWriteAnEmptyObject(): void
    {
        $themeJson = new ThemeJson(new Config(), new Presets());

        $themeJson->settings()->typography()->fluid();

        $this->assertNull($themeJson->get('settings.typography.fluid'));
    }

    /**
     * @dataProvider globalFluidConfigurationProvider
     */
    public function testItShouldAllowClampWhenFluidIsDisabledForFontSize(callable $configureGlobalFluid): void
    {
        $presets = new Presets();
        $config = new Config();
        $themeJson = new ThemeJson($config, $presets);
        $typography = $themeJson->settings()->typography();
        $configureGlobalFluid($typography);

        $typography->addFontSize(
            'custom-clamp',
            'Custom clamp',
            'clamp(1rem, 2vw, 1.5rem)',
            false
        );

        (new PresetsToThemeJson())($config, $presets);

        $this->assertFalse(
            $themeJson->get('settings.typography.fontSizes.0.fluid')
        );
        $this->assertSame(
            'clamp(1rem, 2vw, 1.5rem)',
            $themeJson->get('settings.typography.fontSizes.0.size')
        );
    }

    /** @return iterable<string, array{callable(Typography): mixed}> */
    public static function globalFluidConfigurationProvider(): iterable
    {
        yield 'enabled' => [
            static fn (Typography $typography): Typography => $typography->enableFluid(),
        ];
        yield 'configured' => [
            static fn (Typography $typography): mixed => $typography->fluid()->minFontSize('1rem'),
        ];
    }
}
