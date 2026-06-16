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

    public function testItCharacterizesUnrelatedSelectorProcessingWithWordPressThemeJson(): void
    {
        $selector = '.test-selector';
        $actual = '.other-selector{color: red;}';

        $parsed = $this->makeInstance()->compressed()->parse($actual, $selector);
        $processedParsedCss = $this->processBlocksCustomCssWithWordPress($parsed, $selector);
        $processedActualCss = $this->processBlocksCustomCssWithWordPress($actual, $selector);

        $this->assertSame('', $parsed);
        $this->assertSame('', $processedParsedCss);
        $this->assertSame(':root :where(.test-selector.other-selector){color: red;}', $processedActualCss);
        $this->assertNotSame($processedActualCss, $processedParsedCss);
    }

    /**
     * @dataProvider scopedAtRuleProvider
     */
    public function testItShouldNotHoistAtRuleDeclarationsIntoScopedWordPressCss(
        string $actual,
        string $expectedWordPressCss
    ): void {
        $selector = '.test-selector';

        $parsed = $this->makeInstance()->compressed()->parse($actual, $selector);
        $processedParsedCss = $this->processBlocksCustomCssWithWordPress($parsed, $selector);

        $this->assertSame($expectedWordPressCss, $processedParsedCss);
    }

    public static function scopedAtRuleProvider(): iterable
    {
        yield '@media' => [
            'actual' => '.test-selector{color:red;}@media (min-width: 600px){.test-selector{color:blue;}}',
            'expectedWordPressCss' => ':root :where(.test-selector){color: red;}',
        ];

        yield '@supports' => [
            'actual' => '.test-selector{display:block;}@supports (display: grid){.test-selector{display:grid;}}',
            'expectedWordPressCss' => ':root :where(.test-selector){display: block;}',
        ];
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
