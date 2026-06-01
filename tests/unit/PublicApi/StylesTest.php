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
        $this->assertSame(' a{color: red;}', $sut->get('styles.blocks.core/paragraph.css'));
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
