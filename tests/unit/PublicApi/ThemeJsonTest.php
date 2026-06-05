<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings;
use ItalyStrap\ThemeJsonGenerator\Settings\Border\RadiusSize;
use ItalyStrap\ThemeJsonGenerator\Settings\Color as SettingsColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Duotone;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Gradient;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Palette;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\Color as ColorValue;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\LinearGradient;
use ItalyStrap\ThemeJsonGenerator\Settings\Custom;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class ThemeJsonTest extends UnitTestCase
{
    private function makeInstance(): ThemeJson
    {
        return new ThemeJson(
            new Config(),
            new Presets(),
        );
    }

    public function testItShouldImplementsJsonSerializable(): void
    {
        $sut = $this->makeInstance();
        $this->assertInstanceOf(\JsonSerializable::class, $sut, 'Should implements JsonSerializable');
    }

    public function testItShouldBeJsonSerializable(): void
    {
        $sut = $this->makeInstance();
        $sut->merge([
            'foo' => 'bar',
            'baz' => 'qux',
        ]);

        $this->assertJsonStringEqualsJsonString(
            '{"foo":"bar","baz":"qux"}',
            \json_encode($sut),
            'Json encode should be equals'
        );
    }

    public function testItShouldNotExposeInternalPresetHydrationMethod(): void
    {
        $this->assertFalse(\method_exists($this->makeInstance(), 'setPresets'));
    }

    public function testItShouldExposeTopLevelRootMethods(): void
    {
        $sut = $this->makeInstance();

        $result = $sut
            ->schema('https://schemas.wp.org/trunk/theme.json')
            ->version(3)
            ->title('Moduli')
            ->slug('moduli')
            ->description('Theme metadata');

        $this->assertSame($sut, $result);

        $this->assertSame('https://schemas.wp.org/trunk/theme.json', $sut->get('$schema'));
        $this->assertSame(3, $sut->get('version'));
        $this->assertSame('Moduli', $sut->get('title'));
        $this->assertSame('moduli', $sut->get('slug'));
        $this->assertSame('Theme metadata', $sut->get('description'));
    }

    public function testItShouldExposeTopLevelRootCollectionObjects(): void
    {
        $sut = $this->makeInstance();

        $sut->blockTypes()
            ->add('core/paragraph')
            ->add('core/heading');
        $sut->customTemplates()->addTemplate('landing', 'Landing');
        $sut->templateParts()->addPart('header', 'header', 'Header');
        $sut->patterns()->add('moduli/hero');

        $this->assertSame(['core/paragraph', 'core/heading'], $sut->get('blockTypes'));
        $this->assertSame([['name' => 'landing', 'title' => 'Landing']], $sut->get('customTemplates'));
        $this->assertSame([['name' => 'header', 'title' => 'Header', 'area' => 'header']], $sut->get('templateParts'));
        $this->assertSame(['moduli/hero'], $sut->get('patterns'));
    }

    public function testItShouldExposeSettingsFacade(): void
    {
        $sut = $this->makeInstance();

        $sut->settings()->color()->disableCustom();
        $this->assertFalse($sut->get('settings.color.custom'));
    }

    public function testItShouldExposeStylesFacade(): void
    {
        $sut = $this->makeInstance();

        $this->assertTrue($sut->styles()->css('body{color:red;}'));
        $this->assertTrue($sut->styles()->appendCss('a{color:blue;}'));
        $this->assertTrue($sut->styles()->set('color.text', 'var(--wp--preset--color--base)'));
        $sut->styles()->background()->backgroundImage('url(hero.jpg)');
        $sut->styles()->color()->text('#111111');
        $sut->styles()->border()->color('#222222')->width('1px');
        $sut->styles()->border()->top()->color('#333333');
        $sut->styles()->dimensions()->aspectRatio('16/9')->height('100%')->minHeight('10rem');
        $sut->styles()->filter()->duotone('var:preset|duotone|brand');
        $sut->styles()->outline()->color('#444444')->offset('2px')->style('solid')->width('1px');
        $this->assertTrue($sut->styles()->shadow('0 1px 2px 0 rgb(0 0 0 / 0.05)'));
        $sut->styles()->spacing()->blockGap('1rem');
        $sut->styles()->spacing()->margin()->top('2rem');
        $sut->styles()->spacing()->padding()->horizontal('3rem');
        $sut->styles()->spacing()->blockGap('4rem');
        $sut->styles()->spacing()->margin()->shorthand(['0px']);
        $sut->styles()->spacing()->padding()->shorthand(['0px']);
        $sut->styles()->typography()->lineHeight('1.5');

        $this->assertSame('body{color:red;}a{color:blue;}', $sut->get('styles.css'));
        $this->assertSame('url(hero.jpg)', $sut->get('styles.background.backgroundImage'));
        $this->assertSame('#111111', $sut->get('styles.color.text'));
        $this->assertSame('#222222', $sut->get('styles.border.color'));
        $this->assertSame('1px', $sut->get('styles.border.width'));
        $this->assertSame('#333333', $sut->get('styles.border.top.color'));
        $this->assertSame('16/9', $sut->get('styles.dimensions.aspectRatio'));
        $this->assertSame('100%', $sut->get('styles.dimensions.height'));
        $this->assertSame('10rem', $sut->get('styles.dimensions.minHeight'));
        $this->assertSame('var:preset|duotone|brand', $sut->get('styles.filter.duotone'));
        $this->assertSame('#444444', $sut->get('styles.outline.color'));
        $this->assertSame('2px', $sut->get('styles.outline.offset'));
        $this->assertSame('solid', $sut->get('styles.outline.style'));
        $this->assertSame('1px', $sut->get('styles.outline.width'));
        $this->assertSame('0 1px 2px 0 rgb(0 0 0 / 0.05)', $sut->get('styles.shadow'));
        $this->assertSame('4rem', $sut->get('styles.spacing.blockGap'));
        $this->assertSame('0px', $sut->get('styles.spacing.margin.top'));
        $this->assertSame('0px', $sut->get('styles.spacing.padding.top'));
        $this->assertSame('0px', $sut->get('styles.spacing.padding.left'));
        $this->assertNull($sut->get('styles.spacing.margin.padding'));
        $this->assertSame('1.5', $sut->get('styles.typography.lineHeight'));
    }

    public function testItShouldExposeSettingsAreaFacades(): void
    {
        $presets = new Presets();
        $sut = new ThemeJson(new Config(), $presets);
        $settings = $sut->settings();

        $this->assertInstanceOf(
            Settings::class,
            $settings->enableUseRootPaddingAwareAlignments()
        );
        $settings->disableUseRootPaddingAwareAlignments();
        $settings->background()->enableBackgroundImage()->enableBackgroundSize()->disableGradient();
        $settings->border()
            ->enableColor()
            ->enableRadius()
            ->disableStyle()
            ->enableWidth()
            ->addRadiusSize('small', 'Small', '4px');
        $this->assertTrue($settings->dimensions()->set('aspectRatio', true));
        $settings->layout()->contentSize('960px');
        $settings->lightbox()->enableEditing();
        $settings->position()->enableSticky();
        $settings->shadow()->disableDefaultPresets();
        $settings->spacing()->enablePadding();
        $this->assertInstanceOf(Typography::class, $settings->typography()->addFontSize('body', 'Body', '1rem'));
        $settings->typography()->enableFontStyle();
        $this->assertInstanceOf(Custom::class, $settings->custom()->add('spacing.base', '1rem'));
        $this->assertInstanceOf(Custom::class, $settings->custom()->addMultiple([
            'spacing' => [
                'large' => '3rem',
            ],
        ]));
        $this->assertTrue($settings->custom()->set('brand.primary', '#111111'));

        $this->assertTrue($sut->get('settings.background.backgroundImage'));
        $this->assertTrue($sut->get('settings.background.backgroundSize'));
        $this->assertFalse($sut->get('settings.background.gradient'));
        $this->assertFalse($sut->get('settings.useRootPaddingAwareAlignments'));
        $this->assertTrue($sut->get('settings.border.color'));
        $this->assertTrue($sut->get('settings.border.radius'));
        $this->assertFalse($sut->get('settings.border.style'));
        $this->assertTrue($sut->get('settings.border.width'));
        $this->assertInstanceOf(RadiusSize::class, $presets->get('borderRadius.small'));
        $this->assertTrue($sut->get('settings.dimensions.aspectRatio'));
        $this->assertSame('960px', $sut->get('settings.layout.contentSize'));
        $this->assertTrue($sut->get('settings.lightbox.allowEditing'));
        $this->assertTrue($sut->get('settings.position.sticky'));
        $this->assertFalse($sut->get('settings.shadow.defaultPresets'));
        $this->assertTrue($sut->get('settings.spacing.padding'));
        $this->assertTrue($sut->get('settings.typography.fontStyle'));
        $this->assertSame('var(--wp--preset--font-size--body)', $presets->get('fontSize.body')->var());
        $this->assertSame('1rem', (string)$presets->get('custom.spacing.base'));
        $this->assertSame('3rem', (string)$presets->get('custom.spacing.large'));
        $this->assertSame('#111111', $sut->get('settings.custom.brand.primary'));
    }

    public function testItShouldExposeSettingsColorSpecificMethods(): void
    {
        $presets = new Presets();
        $sut = new ThemeJson(new Config(), $presets);
        $color = $sut->settings()->color();

        $base = new Palette('base', 'Base', new ColorValue('#000000'));
        $contrast = new Palette('contrast', 'Contrast', new ColorValue('#ffffff'));

        $this->assertInstanceOf(SettingsColor::class, $color->disableLink());
        $this->assertFalse($sut->get('settings.color.link'));

        $this->assertInstanceOf(SettingsColor::class, $color->addColor('accent', 'Accent', '#ff0000'));
        $this->assertInstanceOf(Palette::class, $presets->get('color.accent'));
        $this->assertInstanceOf(SettingsColor::class, $color->addColors((static function (): iterable {
            yield new Palette('muted', 'Muted', new ColorValue('#cccccc'));
        })()));
        $this->assertInstanceOf(Palette::class, $presets->get('color.muted'));

        $gradient = (new LinearGradient())
            ->colorStop('#000000')
            ->colorStop('#ffffff');
        $this->assertInstanceOf(SettingsColor::class, $color->addGradient('fade', 'Fade', $gradient));
        $this->assertInstanceOf(Gradient::class, $presets->get('gradient.fade'));

        $this->assertInstanceOf(
            SettingsColor::class,
            $color->addDuotone('brand', 'Brand', $base, $contrast)
        );
        $this->assertInstanceOf(Duotone::class, $presets->get('duotone.brand'));
        $this->assertSame(
            [
                'slug' => 'brand',
                'name' => 'Brand',
                'colors' => [
                    'rgba(0,0,0,1.00)',
                    'rgba(255,255,255,1.00)',
                ],
            ],
            $presets->get('duotone.brand')->toArray()
        );
    }

    public function testItShouldExposeElementsFacade(): void
    {
        $sut = $this->makeInstance();

        $sut->styles()->elements('button')->color()->text('#111111');
        $sut->styles()->elements(['button', ':hover'])->color()->background('#222222');
        $sut->styles()->elements(['button', ':focus'])->outline()->color('#333333')->style('dotted');
        $sut->styles()->elements('button')->dimensions()->minWidth('12rem');
        $sut->styles()->elements('button')->filter()->duotone('var:preset|duotone|button');
        $sut->styles()->elements('button')->shadow('0 1px 2px 0 rgb(0 0 0 / 0.05)');
        $this->assertTrue($sut->styles()->elements('button')->css(':focus{outline:none;}'));
        $sut->styles()->elements('button')->border()->radius('4px');
        $sut->styles()->elements('button')->border()->bottom()->style('solid');
        $sut->styles()->elements('button')->spacing()->padding()->vertical('1rem');

        $this->assertSame('#111111', $sut->get('styles.elements.button.color.text'));
        $this->assertSame('#222222', $sut->get('styles.elements.button.:hover.color.background'));
        $this->assertSame('#333333', $sut->get('styles.elements.button.:focus.outline.color'));
        $this->assertSame('dotted', $sut->get('styles.elements.button.:focus.outline.style'));
        $this->assertSame('12rem', $sut->get('styles.elements.button.dimensions.minWidth'));
        $this->assertSame('var:preset|duotone|button', $sut->get('styles.elements.button.filter.duotone'));
        $this->assertSame('0 1px 2px 0 rgb(0 0 0 / 0.05)', $sut->get('styles.elements.button.shadow'));
        $this->assertSame(':focus{outline:none;}', $sut->get('styles.elements.button.css'));
        $this->assertSame('4px', $sut->get('styles.elements.button.border.radius'));
        $this->assertSame('solid', $sut->get('styles.elements.button.border.bottom.style'));
        $this->assertSame('1rem', $sut->get('styles.elements.button.spacing.padding.top'));
        $this->assertSame('1rem', $sut->get('styles.elements.button.spacing.padding.bottom'));
    }

    public function testItShouldExposeBlocksFacade(): void
    {
        $sut = $this->makeInstance();

        $this->assertTrue($sut->settings()->blocks('core/paragraph')->set('color.text', true));
        $sut->settings()->blocks('core/image')->color()->disableText();
        $this->assertTrue($sut->styles()->set(['blocks', 'core/paragraph'], ['color' => ['text' => 'red']]));
        $this->assertTrue(
            $sut->styles()->blocks('core/paragraph')->css('.wp-block-paragraph a{color:red;}', '.wp-block-paragraph')
        );
        $this->assertTrue(
            $sut->styles()->blocks('core/paragraph')->appendCss(
                '.wp-block-paragraph strong{font-weight:700;}',
                '.wp-block-paragraph'
            )
        );
        $sut->styles()->blocks('core/image')->color()->text('#333333');
        $sut->styles()->blocks('core/image')->border()->style('solid');
        $sut->styles()->blocks('core/image')->border()->left()->width('2px');
        $sut->styles()->blocks('core/image')->dimensions()->width('640px');
        $sut->styles()->blocks('core/image')->filter()->duotone('var:preset|duotone|image');
        $sut->styles()->blocks('core/image')->outline()->width('3px');
        $sut->styles()->blocks('core/image')->spacing()->margin()->bottom('4rem');
        $sut->styles()->blocks('core/button')->spacing()->padding()->vertical('1rem')->horizontal('2rem');
        $sut->styles()->blocks('core/button')->variations('outline')->background()->backgroundImage('url(button.jpg)');
        $sut->styles()->blocks('core/button')->variations('outline')->color()->text('#555555');
        $sut->styles()->blocks('core/button')->variations('outline')->typography()->fontWeight('700');
        $this->assertTrue($sut->styles()->blocks('core/heading')->shadow('0 1px 2px 0 rgb(0 0 0 / 0.05)'));

        $this->assertTrue($sut->get('settings.blocks.core/paragraph.color.text'));
        $this->assertFalse($sut->get('settings.blocks.core/image.color.text'));
        $this->assertSame(['text' => 'red'], $sut->get('styles.blocks.core/paragraph.color'));
        $this->assertSame(
            <<<CSS
 a {
    color: red;
}& strong {
    font-weight: 700;
}
CSS,
            $sut->get('styles.blocks.core/paragraph.css')
        );
        $this->assertSame('#333333', $sut->get('styles.blocks.core/image.color.text'));
        $this->assertSame('solid', $sut->get('styles.blocks.core/image.border.style'));
        $this->assertSame('2px', $sut->get('styles.blocks.core/image.border.left.width'));
        $this->assertSame('640px', $sut->get('styles.blocks.core/image.dimensions.width'));
        $this->assertSame('var:preset|duotone|image', $sut->get('styles.blocks.core/image.filter.duotone'));
        $this->assertSame('3px', $sut->get('styles.blocks.core/image.outline.width'));
        $this->assertSame('4rem', $sut->get('styles.blocks.core/image.spacing.margin.bottom'));
        $this->assertSame('1rem', $sut->get('styles.blocks.core/button.spacing.padding.top'));
        $this->assertSame('1rem', $sut->get('styles.blocks.core/button.spacing.padding.bottom'));
        $this->assertSame('2rem', $sut->get('styles.blocks.core/button.spacing.padding.right'));
        $this->assertSame('2rem', $sut->get('styles.blocks.core/button.spacing.padding.left'));
        $this->assertNull($sut->get('styles.blocks.core/button.spacing.right'));
        $this->assertSame(
            'url(button.jpg)',
            $sut->get('styles.blocks.core/button.variations.outline.background.backgroundImage')
        );
        $this->assertSame('#555555', $sut->get('styles.blocks.core/button.variations.outline.color.text'));
        $this->assertSame('700', $sut->get('styles.blocks.core/button.variations.outline.typography.fontWeight'));
        $this->assertSame('0 1px 2px 0 rgb(0 0 0 / 0.05)', $sut->get('styles.blocks.core/heading.shadow'));
    }
}
