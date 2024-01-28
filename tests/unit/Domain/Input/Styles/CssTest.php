<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Styles;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\CssExperimental;
use ScssPhp\ScssPhp\Compiler;

/**
 * @link https://make.wordpress.org/core/2023/03/06/custom-css-for-global-styles-and-per-block/
 * @link https://fullsiteediting.com/lessons/how-to-use-custom-css-in-theme-json/
 * @link https://developer.wordpress.org/news/2023/04/21/per-block-css-with-theme-json/
 * @link https://github.com/WordPress/wordpress-develop/blob/trunk/tests/phpunit/tests/theme/wpThemeJson.php
 *
 * @link https://www.google.it/search?q=php+inline+css+content&sca_esv=596560865&ei=mAicZaTCGp3Axc8Pq7yT8AQ&ved=0ahUKEwik7p-Rgs6DAxUdYPEDHSveBE4Q4dUDCBA&uact=5&oq=php+inline+css+content&gs_lp=Egxnd3Mtd2l6LXNlcnAiFnBocCBpbmxpbmUgY3NzIGNvbnRlbnQyBRAhGKABMgUQIRigATIIECEYFhgeGB0yCBAhGBYYHhgdMggQIRgWGB4YHUjvogFQmgdYwJcBcAF4AZABAJgBsQGgAZkSqgEEMC4xOLgBA8gBAPgBAcICChAAGEcY1gQYsAPCAgoQABiABBiKBRhDwgIFEAAYgATCAgYQABgWGB7CAgcQABiABBgTwgIIEAAYFhgeGBPiAwQYACBBiAYBkAYI&sclient=gws-wiz-serp#ip=1
 * @link https://github.com/topics/inline-css?l=php
 * @link https://github.com/sabberworm/PHP-CSS-Parser
 */
class CssTest extends UnitTestCase
{
    private function makeInstance(): CssExperimental
    {
        return new CssExperimental();
    }

    public static function styleProvider(): iterable
    {
        yield 'root element' => [
            'actual' => 'height: 100%;',
            'expected' => 'height: 100%;',
        ];

        yield 'root element with multiple rules' => [
            'actual' => 'height: 100%;width: 100%;color: red;',
            'expected' => 'height: 100%;width: 100%;color: red;',
        ];

        yield 'root element with multiple rules and pseudo class' => [
            'actual' => 'height: 100%;width: 100%;color: red;:hover {color: red;}',
            'expected' => 'height: 100%;width: 100%;color: red;' . PHP_EOL . '&:hover {color: red;}',
        ];

        yield 'root element with multiple rules and pseudo class and pseudo element' => [
            'actual' => 'height: 100%;width: 100%;color: red;:hover {color: red;}::placeholder {color: red;}',
            'expected' =>
                'height: 100%;width: 100%;color: red;'
                . PHP_EOL . '&:hover {color: red;}'
                . PHP_EOL . '&::placeholder {color: red;}',
        ];

        yield 'pseudo single class' => [
            'actual' => ':hover {color: red;}',
            'expected' => '&:hover {color: red;}',
        ];

        yield 'pseudo single class with multiple rules' => [
            'actual' => ':hover {color: red;height: 100%;}',
            'expected' => '&:hover {color: red;height: 100%;}',
        ];

        yield 'pseudo single class with multiple rules and pseudo element' => [
            'actual' => ':hover {color: red;height: 100%;}::placeholder {color: red;}',
            'expected' => '&:hover {color: red;height: 100%;}' . PHP_EOL . '&::placeholder {color: red;}',
        ];

        yield 'simple pseudo element ::placeholder ' => [
            'actual' => '::placeholder {color: red;}',
            'expected' => '&::placeholder {color: red;}',
        ];

        yield 'simple pseudo element with multiple rules ::placeholder ' => [
            'actual' => '::placeholder {color: red;height: 100%;}',
            'expected' => '&::placeholder {color: red;height: 100%;}',
        ];

        yield 'root element with pseudo element' => [
            'actual' => 'height: 100%;::placeholder {color: red;}',
            'expected' => 'height: 100%;' . PHP_EOL . '&::placeholder {color: red;}',
        ];

        yield 'mixed css example' => [
            'actual' => <<<'MIXED_CSS'
height: 100%;
.foo {
    color: red;
}
MIXED_CSS,
            'expected' => <<<'MIXED_CSS'
height: 100%;
& .foo {
    color: red;
}
MIXED_CSS,
        ];

        yield 'mixed css example with multiple rules' => [
            'actual' => <<<'MIXED_CSS'
height: 100%;
width: 100%;
.foo {
    color: red;
    height: 100%;
}
MIXED_CSS,
            'expected' => <<<'MIXED_CSS'
height: 100%;
width: 100%;
& .foo {
    color: red;
    height: 100%;
}
MIXED_CSS,
        ];

        yield 'simple css example' => [
            'actual' => <<<'CSS'
.foo {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
}
CSS,
            'expected' => <<<'CSS'
& .foo {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
}
CSS,
        ];

        yield 'simple css example with multiple rules' => [
            'actual' => <<<'CSS'
.foo {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
.foo .bar {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
CSS,
            'expected' => <<<'CSS'
& .foo {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
& .foo .bar {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
CSS,
        ];

        yield 'simple css example with multiple rules and pseudo class' => [
            'actual' => <<<'CSS'
.foo {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
.foo .bar {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
table {
    border-collapse: collapse;
    border-spacing: 0;
}
:hover {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
CSS,
            'expected' => <<<'CSS'
& .foo {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
& .foo .bar {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
& table {
    border-collapse: collapse;
    border-spacing: 0;
}
&:hover {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
CSS,
        ];

        yield 'simple css example with multiple rules and pseudo class and pseudo element' => [
            'actual' => <<<'CSS'
.foo {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
.foo .bar {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
table {
    border-collapse: collapse;
    border-spacing: 0;
}
:hover {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
::placeholder {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
CSS,
            'expected' => <<<'CSS'
& .foo {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
& .foo .bar {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
& table {
    border-collapse: collapse;
    border-spacing: 0;
}
&:hover {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
&::placeholder {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
CSS,
        ];

        yield 'root custom properties' => [
            'actual' => <<<'CUSTOM_CSS'
--foo: 100%;
CUSTOM_CSS,
            'expected' => <<<'CUSTOM_CSS'
--foo: 100%;
CUSTOM_CSS,
        ];

        yield 'root custom properties with multiple rules' => [
            'actual' => <<<'CUSTOM_CSS'
--foo: 100%;
--bar: 100%;
CUSTOM_CSS,
            'expected' => <<<'CUSTOM_CSS'
--foo: 100%;
--bar: 100%;
CUSTOM_CSS,
        ];

        yield 'root custom properties mixed with css' => [
            'actual' => <<<'CUSTOM_CSS'
#firstParagraph {
    background-color: var(--first-color);
    color: var(--second-color);
}
--foo: 100%;
--bar: 100%;
.foo {
    --bar: 50%;
    color: red;
    width: var(--foo);
    height: var(--bar);
}
CUSTOM_CSS,
            'expected' => <<<'CUSTOM_CSS'
& #firstParagraph {
    background-color: var(--first-color);
    color: var(--second-color);
}
--foo: 100%;
--bar: 100%;
& .foo {
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
    public function testItShouldParse(string $css, string $expected): void
    {
        $actual = $this->makeInstance()->parseString($css);
        $this->assertSame($expected, $actual);
    }

    /**
     * @dataProvider styleProvider
     */
    public function testItShouldCompileExpanded(string $css, string $expected): void
    {
        $this->expandedCompiler($css, 'expanded');
    }

    /**
     * @dataProvider styleProvider
     */
    public function testItShouldCompileCompressed(string $css, string $expected): void
    {
        $this->expandedCompiler($css, 'compressed');
    }

    private function expandedCompiler(string $css, string $style): void
    {
        $compiler = new Compiler();
        $compiler->setOutputStyle($style);

        $result = $compiler->compileString($css);

        $actual = $this->makeInstance()->parseString($result->getCss());
        $this->assertTrue(true, 'Let this test pass, is a check for the compiler');
    }
}
