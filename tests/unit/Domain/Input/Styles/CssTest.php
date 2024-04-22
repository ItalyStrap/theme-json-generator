<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Styles;

use ItalyStrap\Tests\CssStyleStringProviderTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Css;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Scss;
use ScssPhp\ScssPhp\Compiler;

class CssTest extends UnitTestCase
{
    use CssStyleStringProviderTrait {
        CssStyleStringProviderTrait::styleProvider as styleProviderTrait;
        CssStyleStringProviderTrait::newStyleProvider as newStyleProviderTrait;
    }

    private function makeInstance(): Css
    {
        return new Css();
    }

    public static function styleProvider(): iterable
    {
        foreach (self::styleProviderTrait() as $key => $value) {
            yield $key => $value;
        }

        yield 'root custom properties mixed with css' => [
            // phpcs:disable
            'selector' => '.test-selector',
            'actual' => '.test-selector #firstParagraph{background-color: var(--first-color);color: var(--second-color);}.test-selector{--foo: 100%;--bar: 100%;}.test-selector .foo {--bar: 50%;color: red;width: var(--foo);height: var(--bar);}',
            'expected' => '--foo: 100%;--bar: 100%;& #firstParagraph{background-color: var(--first-color);color: var(--second-color);}& .foo {--bar: 50%;color: red;width: var(--foo);height: var(--bar);}',
            // phpcs:enable
        ];

        yield 'css on multiple line' => [
            'selector' => '.test-selector',
            'actual' => <<<'CUSTOM_CSS'
.test-selector .bar{
    color: red;
}
.test-selector {
    --foo: 100%;
    height: 100%;
    width: 100%;
    color: red;
}
.test-selector .foo{
    --bar: 50%;
    color: red;
    width: var(--foo);
    height: var(--bar);
}
CUSTOM_CSS,
            'expected' => <<<'CUSTOM_CSS'
--foo: 100%;
height: 100%;
width: 100%;
color: red;
& .bar{
    color: red;
}
& .foo{
    --bar: 50%;
    color: red;
    width: var(--foo);
    height: var(--bar);
}
CUSTOM_CSS,
        ];

        yield 'with list selectors' => [
            // phpcs:disable
            'selector' => '.test-selector',
            'original' => '.test-selector .one ,.test-selector .two,.test-selector .three{color: red;}',
            'expected' => " .one {color: red;}\n& .two {color: red;}\n& .three {color: red;}\n",
            // phpcs:enable
        ];

        yield 'with list selectors and new line' => [
            // phpcs:disable
            'selector' => '.test-selector',
            'original' => ".test-selector .one ,.test-selector .two,.test-selector .three{\ncolor: red;\n}",
            'expected' => " .one {\ncolor: red;\n}\n& .two {\ncolor: red;\n}\n& .three {\ncolor: red;\n}\n",
            // phpcs:enable
        ];

        yield 'with list selectors and new line without original selector' => [
            // phpcs:disable
            'selector' => '',
            'original' => " .one,& .two,& .three{\ncolor: red;\n}",
            'expected' => " .one {\ncolor: red;\n}\n& .two {\ncolor: red;\n}\n& .three {\ncolor: red;\n}\n",
            // phpcs:enable
        ];
    }

    /**
     * @dataProvider styleProvider
     */
    public function testItShouldParse(string $selector, string $actual, string $expected): void
    {
        $parseString = $this->makeInstance()->parseString($actual, $selector);
        $this->assertSame($expected, $parseString, 'The parsed string is not the same as expected');
    }

    public function testItShouldThrowErrorIfCssStartWithAmpersand(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(Css::M_AMPERSAND_MUST_NOT_BE_AT_THE_BEGINNING);

        $this->makeInstance()->parse('& .foo{color: red;}');
    }

    /**
     * @dataProvider styleProvider
     */
    public function testItShouldCompileExpanded(string $selector, string $css, string $expected): void
    {
        $this->expandedCompiler($css, 'expanded');
    }

    /**
     * @dataProvider styleProvider
     */
    public function testItShouldCompileCompressed(string $selector, string $css, string $expected): void
    {
        $this->expandedCompiler($css, 'compressed');
    }

    private function expandedCompiler(string $css, string $style): void
    {
        $compiler = new Compiler();
        $compiler->setOutputStyle($style);

        $result = $compiler->compileString($css);

        $actual = $this->makeInstance()->parse($result->getCss(), '.test-selector');
        $this->assertTrue(true, 'Let this test pass, is a check for the compiler');
    }

    public static function newStyleProvider(): iterable
    {
        foreach (self::newStyleProviderTrait() as $key => $value) {
            yield $key => $value;
        }

        yield 'selector list' => [
            // phpcs:disable
            'selector' => '.test-selector',
            'actual' => '.test-selector .one ,.test-selector .two,.test-selector.three,.test-selector #four{color: red;}',
            'expected' => ' .one{color: red;}& .two{color: red;}&.three{color: red;}& #four{color: red;}',
            // phpcs:enable
        ];

        yield 'selector used also as prefix for nested selectors' => [
            // phpcs:disable
            'selector' => '.test-selector',
            'actual' => '.test-selector .test-selector-one{color: blue;}.test-selector .test-selector-two{color: red;}',
            'expected' => ' .test-selector-one{color: blue;}& .test-selector-two{color: red;}',
        ];
    }

    /**
     * @dataProvider newStyleProvider
     */
    public function testItShouldParseWithNewMethod(string $selector, string $actual, string $expected): void
    {
        $parseString = $this->makeInstance()->parse($actual, $selector);
        $this->assertSame($expected, $parseString, 'The parsed string is not the same as expected');
    }

    public function testCssParser(): void
    {
        $css =  <<<CSS
.wp-block-query-pagination {
    gap: 0;
}
.wp-block-query-pagination.page-numbers,
.wp-block-query-pagination #page-numbers,
.wp-block-query-pagination .page-numbers,
.wp-block-query-pagination .wp-block-query-pagination-previous,
.wp-block-query-pagination .wp-block-query-pagination-next {
    border-color: #ddd;
    border-width: 1px;
    border-style: solid;
    padding: .375em .75em;
    margin: 0 0 0 -1px;
}
.wp-block-query-pagination .wp-block-query-pagination-numbers {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0;
}
.wp-block-query-pagination {
    margin: 0;
    --margin: 0;
}
CSS;
        $selector = '.wp-block-query-pagination';
        $sut = $this->makeInstance();
//        codecept_debug($sut->parse($css, $selector));
    }

    public function testScssParser(): void
    {
        $css =  <<<CSS
.wp-block-query-pagination {
    gap: 0;
    margin: 0;
    --margin: 0;
    &.page-numbers,
    & #page-numbers,
    & .page-numbers,
    & .wp-block-query-pagination-previous,
    & .wp-block-query-pagination-next {
        border-color: #ddd;
        border-width: 1px;
        border-style: solid;
        padding: .375em .75em;
        margin: 0 0 0 -1px;
    }
    & .wp-block-query-pagination-numbers {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
    }
}
CSS;
        $selector = '.wp-block-query-pagination';
        $sut = $this->makeInstance();
//        codecept_debug($sut->parse($css, $selector));

//        $compiler = new Compiler();
//        $compiler->setOutputStyle('expanded');
//
//        $result = $compiler->compileString($css);
//
//        codecept_debug($result->getCss());
//
//        $actual = $this->makeInstance()->expanded()->parse($result->getCss(), '.wp-block-query-pagination');
//        codecept_debug($actual);

        $scss = new Scss($sut, new Compiler());
        $result = $scss->expanded()->parse($css, $selector);
//        codecept_debug($result);
    }
}
