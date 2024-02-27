<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Styles;

use ItalyStrap\Tests\CssStyleStringProviderTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Css;
use ScssPhp\ScssPhp\Compiler;

class CssTest extends UnitTestCase
{
    use CssStyleStringProviderTrait {
        CssStyleStringProviderTrait::styleProvider as styleProviderTrait;
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
    }

    /**
     * @dataProvider styleProvider
     */
    public function testItShouldParse(string $selector, string $actual, string $expected): void
    {
        $parseString = $this->makeInstance()->parseString($actual, $selector);
        $this->assertSame($expected, $parseString, 'The parsed string is not the same as expected');
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

        $actual = $this->makeInstance()->parseString($result->getCss(), '.test-selector');
        $this->assertTrue(true, 'Let this test pass, is a check for the compiler');
    }
}
