<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Styles;

use ItalyStrap\Tests\CssParserScenarioProviderTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Styles\Css;
use ItalyStrap\ThemeJsonGenerator\Styles\CssInterface;

final class CssTest extends UnitTestCase
{
    use CssParserScenarioProviderTrait;

    private function makeInstance(): Css
    {
        return new Css();
    }

    public function testItShouldPreserveWordPressScopedCssStartingWithAmpersand(): void
    {
        $this->assertSame(
            '& .foo{color: red;}',
            $this->makeInstance()->parse('& .foo{color: red;}')
        );
    }

    public function testItShouldParseExpandedCssByDefault(): void
    {
        $actual = '.test-selector{height: 100%;width: 100%;color: red;}.test-selector:hover {color: red;}';
        $expected = <<<CSS
height: 100%;
width: 100%;
color: red;

&:hover {
    color: red;
}
CSS;

        $parseString = $this->makeInstance()->parse($actual, '.test-selector');
        $this->assertSame($expected, $parseString, 'The parsed string is not the same as expected');
    }

    /**
     * @dataProvider unrelatedScopedSelectorProvider
     */
    public function testItShouldRejectUnrelatedSelectorsWhenParsingScopedCss(
        string $css,
        string $selector,
        string $unrelatedSelector
    ): void {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            CssInterface::M_SELECTOR_IS_OUTSIDE_SCOPE,
            $unrelatedSelector,
            $selector,
            $unrelatedSelector
        ));

        $this->makeInstance()->compressed()->parse($css, $selector);
    }

    public static function unrelatedScopedSelectorProvider(): iterable
    {
        yield 'unrelated selector' => [
            'css' => '.other-selector{color:red;}',
            'selector' => '.test-selector',
            'unrelatedSelector' => '.other-selector',
        ];

        yield 'same prefix with hyphen' => [
            'css' => '.test-selector-one{color:blue;}',
            'selector' => '.test-selector',
            'unrelatedSelector' => '.test-selector-one',
        ];

        yield 'same prefix with underscore' => [
            'css' => '.card{color:red;}.card_title{color:blue;}',
            'selector' => '.card',
            'unrelatedSelector' => '.card_title',
        ];
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
        $parseString = $this->makeInstance()->compressed()->parse($actual, $selector);
        $this->assertSame($expectedParsedCss, $parseString, 'The parsed string is not the same as expected');
    }
}
