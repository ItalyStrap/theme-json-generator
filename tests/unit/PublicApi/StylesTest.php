<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Palette;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\Color as ColorValue;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class StylesTest extends UnitTestCase
{
    public function testItShouldComposeDeepStyleContexts(): void
    {
        $sut = new ThemeJson(new Config(), $this->makeStylePresets());

        $sut->styles()->elements(['button', ':hover'])->color()->background('color.base');
        $sut->styles()->blocks('core/pullquote')->elements('cite')->typography()->fontStyle('italic');
        $sut->styles()->blocks('core/button')->variations('outline')->border()->color('#333333');
        $sut->styles()->blocks('core/paragraph')->css(
            '.wp-block-paragraph a{color:red;}',
            '.wp-block-paragraph'
        );

        $this->assertSame(
            'var(--wp--preset--color--base)',
            $sut->get('styles.elements.button.:hover.color.background')
        );
        $this->assertSame('italic', $sut->get('styles.blocks.core/pullquote.elements.cite.typography.fontStyle'));
        $this->assertSame('#333333', $sut->get('styles.blocks.core/button.variations.outline.border.color'));
        $this->assertSame(
            <<<CSS
 a {
    color: red;
}
CSS,
            $sut->get('styles.blocks.core/paragraph.css')
        );
    }

    public function testItShouldParseScssInStyleContexts(): void
    {
        $sut = new ThemeJson(new Config(), $this->makeStylePresets());

        $this->assertTrue($sut->styles()->blocks('core/paragraph')->scss(
            '.wp-block-paragraph { a { color: red; } }',
            '.wp-block-paragraph'
        ));

        $this->assertSame(
            <<<CSS
 a {
    color: red;
}
CSS,
            $sut->get('styles.blocks.core/paragraph.css')
        );
    }

    public function testItShouldAppendScssInStyleContexts(): void
    {
        $sut = new ThemeJson(new Config(), $this->makeStylePresets());

        $sut->styles()->blocks('core/paragraph')->scss(
            '.wp-block-paragraph { a { color: red; } }',
            '.wp-block-paragraph'
        );
        $this->assertTrue($sut->styles()->blocks('core/paragraph')->appendScss(
            '.wp-block-paragraph { strong { font-weight: 700; } }',
            '.wp-block-paragraph'
        ));

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
    }

    public function testItShouldPersistNestedSpacingProperties(): void
    {
        $sut = $this->makeThemeJson();

        $sut->styles()->spacing()->margin()->top('1rem');
        $sut->styles()->spacing()->padding()->horizontal('2rem');

        $this->assertSame('1rem', $sut->get('styles.spacing.margin.top'));
        $this->assertSame('2rem', $sut->get('styles.spacing.padding.right'));
        $this->assertSame('2rem', $sut->get('styles.spacing.padding.left'));
    }

    /**
     * @dataProvider invalidVariationSlugProvider
     */
    public function testItShouldRejectInvalidVariationSlugs(string $variation): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a valid variation slug');

        $this->makeThemeJson()->styles()->blocks('core/button')->variations($variation);
    }

    public static function invalidVariationSlugProvider(): iterable
    {
        yield 'empty' => [''];
        yield 'uppercase' => ['Outline'];
        yield 'starts with number' => ['2-columns'];
        yield 'contains underscore' => ['with_underscore'];
        yield 'contains dot' => ['outline.color'];
    }

    /**
     * @dataProvider invalidStructuralTransitionProvider
     * @param \Closure(ThemeJson): void $transition
     */
    public function testItShouldRejectInvalidStructuralTransitions(\Closure $transition, string $message): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage($message);

        $transition($this->makeThemeJson());
    }

    public static function invalidStructuralTransitionProvider(): iterable
    {
        yield 'elements from elements' => [
            static function (ThemeJson $themeJson): void {
                $themeJson->styles()->elements('button')->elements('button');
            },
            'Cannot chain "elements()" after "elements()": this style structure is not supported.',
        ];

        yield 'blocks from blocks' => [
            static function (ThemeJson $themeJson): void {
                $themeJson->styles()->blocks('core/group')->blocks('core/button');
            },
            'Cannot chain "blocks()" after "blocks()": this style structure is not supported.',
        ];

        yield 'blocks from elements' => [
            static function (ThemeJson $themeJson): void {
                $themeJson->styles()->elements('button')->blocks('core/button');
            },
            'Cannot chain "blocks()" after "elements()": this style structure is not supported.',
        ];

        yield 'variations from variations' => [
            static function (ThemeJson $themeJson): void {
                $themeJson->styles()->variations('outline')->variations('filled');
            },
            'Cannot chain "variations()" after "variations()": this style structure is not supported.',
        ];
    }

    private function makeThemeJson(): ThemeJson
    {
        return new ThemeJson(new Config(), $this->makeStylePresets());
    }

    private function makeStylePresets(): Presets
    {
        $presets = new Presets();
        $presets->add(new Palette('base', 'Base', new ColorValue('#ffffff')));

        return $presets;
    }
}
