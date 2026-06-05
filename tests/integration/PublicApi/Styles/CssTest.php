<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Integration\PublicApi\Styles;

use ItalyStrap\Tests\CssParserScenarioProviderTrait;
use ItalyStrap\Tests\IntegrationTestCase;
use ItalyStrap\ThemeJsonGenerator\Styles\Css;

final class CssTest extends IntegrationTestCase
{
    use CssParserScenarioProviderTrait;

    private function makeInstance(): Css
    {
        return new Css();
    }

    /**
     * @dataProvider cssParserScenarioProvider
     */
    public function testItProcessCssWithWordPressThemeJson(
        string $selector,
        string $actual,
        string $expectedParsedCss,
        string $expectedWordPressCss
    ): void {
        $parseString = $this->makeInstance()->compressed()->parse($actual, $selector);
        $this->assertSame($expectedParsedCss, $parseString, 'The parsed string is not the same as expected');

        $result = $this->processBlocksCustomCssWithWordPress($parseString, $selector);

        $this->assertSame($expectedWordPressCss, $result, 'The WordPress processed CSS is not the same as expected');
    }

    private function processBlocksCustomCssWithWordPress(string $css, string $selector): string
    {
        $wpThemeJson = new \WP_Theme_JSON();
        $method = new \ReflectionMethod(\WP_Theme_JSON::class, 'process_blocks_custom_css');

        $result = $method->invoke($wpThemeJson, $css, $selector);
        $this->assertIsString($result);

        return $result;
    }
}
