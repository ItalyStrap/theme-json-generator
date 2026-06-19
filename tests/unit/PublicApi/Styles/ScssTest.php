<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Styles;

use ItalyStrap\Tests\CssParserScenarioProviderTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Custom\Custom;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\Styles\Css;
use ItalyStrap\ThemeJsonGenerator\Styles\Scss;
use ScssPhp\ScssPhp\Compiler;

final class ScssTest extends UnitTestCase
{
    use CssParserScenarioProviderTrait;

    private function makeInstance(): Scss
    {
        $presets = $this->makePresets();
        return new Scss(new Css($presets), new Compiler(), $presets);
    }

    public function testItShouldBeInstantiable(): void
    {
        $instance = $this->makeInstance();
        $this->assertInstanceOf(Scss::class, $instance);
    }

    public function testItShouldNotDisableVariableResolutionOnSharedCssParser(): void
    {
        $presets = new Presets();
        $presets->add(new Custom('color.base', '#ffffff'));

        $css = new Css($presets);
        $scss = new Scss($css, new Compiler(), $presets);

        $scss->parse('.scope { color: red; }', '.scope');

        $this->assertSame(
            'var(--wp--custom--color--base)',
            $css->parse('{{color.base}}')
        );
    }

    public static function newStyleProvider(): iterable
    {
        yield 'selector used also as prefix for nested selectors' => [
            'selector' => '.test-selector',
            'actual' => <<<CSS
.test-selector {
    gap: 0;

    &.test-selector-one,
    & .test-selector-two {
        color: blue;
    }
}
CSS,
            'expected' => 'gap: 0;&.test-selector-one{color: blue;}& .test-selector-two{color: blue;}',
        ];

        yield 'selector used also as prefix for nested selectors with nested selectors' => [
            'selector' => '.test-selector',
            'actual' => <<<CSS
.test-selector__button-inside {
    & .test-selector__button {
        margin-left: -1px;
        transition: margin-left 0.3s;
    }
}
CSS,
            'expected' => '__button-inside .test-selector__button{margin-left: -1px;transition: margin-left .3s;}',
        ];

        yield 'without selector' => [
            'selector' => '',
            'actual' => <<<CSS
.test-selector {
    gap: 0;

    &.test-selector-one,
    & .test-selector-two {
        color: blue;
    }
}
CSS,
            // phpcs:disable
            'expected' => '.test-selector{gap:0}.test-selector.test-selector-one,.test-selector .test-selector-two{color:blue}',
            // phpcs:enable
        ];
    }

    /**
     * @dataProvider newStyleProvider
     */
    public function testItShouldParseWithNewMethod(string $selector, string $actual, string $expected): void
    {
        $this->presets->parse($actual)->willReturn($actual)->shouldBeCalledTimes(1);
        $parseString = $this->makeInstance()->compress()->parse($actual, $selector);
        $this->assertSame($expected, $parseString, 'The parsed string is not the same as expected');
    }

    /**
     * @dataProvider cssParserScenarioProvider
     */
    public function testItShouldParseCssForWordPressCustomCss(
        string $selector,
        string $actual,
        string $expectedParsedCss,
        string $expectedWordPressCss
    ): void {
        $this->presets->parse($actual)->willReturn($actual)->shouldBeCalledTimes(1);
        $parseString = $this->makeInstance()->compress()->parse($actual, $selector);
        $this->assertSame($expectedParsedCss, $parseString, 'The parsed string is not the same as expected');
    }
}
