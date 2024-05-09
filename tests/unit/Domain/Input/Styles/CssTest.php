<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Styles;

use ItalyStrap\Tests\CssStyleStringProviderTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Css;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\CssInterface;
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
        $this->expectExceptionMessage(CssInterface::M_AMPERSAND_MUST_NOT_BE_AT_THE_BEGINNING);

        $this->makeInstance()->parse('& .foo{color: red;}');
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
}
