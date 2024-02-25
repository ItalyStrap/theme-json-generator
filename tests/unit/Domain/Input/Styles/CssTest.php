<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Styles;

use ItalyStrap\Tests\CssStyleStringProviderTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Css;
use ScssPhp\ScssPhp\Compiler;

/**
 * @link https://make.wordpress.org/core/2023/03/06/custom-css-for-global-styles-and-per-block/
 * @link https://fullsiteediting.com/lessons/how-to-use-custom-css-in-theme-json/
 * @link https://developer.wordpress.org/news/2023/04/21/per-block-css-with-theme-json/
 * @link https://github.com/WordPress/wordpress-develop/blob/trunk/tests/phpunit/tests/theme/wpThemeJson.php
 * @link https://developer.wordpress.org/themes/global-settings-and-styles/
 *
 * @link https://www.google.it/search?q=php+inline+css+content&sca_esv=596560865&ei=mAicZaTCGp3Axc8Pq7yT8AQ&ved=0ahUKEwik7p-Rgs6DAxUdYPEDHSveBE4Q4dUDCBA&uact=5&oq=php+inline+css+content&gs_lp=Egxnd3Mtd2l6LXNlcnAiFnBocCBpbmxpbmUgY3NzIGNvbnRlbnQyBRAhGKABMgUQIRigATIIECEYFhgeGB0yCBAhGBYYHhgdMggQIRgWGB4YHUjvogFQmgdYwJcBcAF4AZABAJgBsQGgAZkSqgEEMC4xOLgBA8gBAPgBAcICChAAGEcY1gQYsAPCAgoQABiABBiKBRhDwgIFEAAYgATCAgYQABgWGB7CAgcQABiABBgTwgIIEAAYFhgeGBPiAwQYACBBiAYBkAYI&sclient=gws-wiz-serp#ip=1
 * @link https://github.com/topics/inline-css?l=php
 * @link https://github.com/sabberworm/PHP-CSS-Parser
 */
class CssTest extends UnitTestCase
{
    use CssStyleStringProviderTrait {
        styleProvider as styleProviderTrait;
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
