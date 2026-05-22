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

    public function testItShouldThrowErrorIfCssStartWithAmpersand(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(CssInterface::M_AMPERSAND_MUST_NOT_BE_AT_THE_BEGINNING);

        $this->makeInstance()->parse('& .foo{color: red;}');
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
        $parseString = $this->makeInstance()->parse($actual, $selector);
        $this->assertSame($expectedParsedCss, $parseString, 'The parsed string is not the same as expected');
    }
}
