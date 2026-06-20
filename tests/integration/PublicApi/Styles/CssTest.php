<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Integration\PublicApi\Styles;

use ItalyStrap\Tests\CssParserScenarioProviderTrait;
use ItalyStrap\Tests\IntegrationTestCase;
use ItalyStrap\ThemeJsonGenerator\Styles\Css;
use ItalyStrap\ThemeJsonGenerator\Styles\CssInterface;

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

        $processedActualCss = $this->processBlocksCustomCssWithWordPress($actual, $selector);

        $this->assertSame(':root :where(.test-selector.other-selector){color: red;}', $processedActualCss);
    }

    public function testItShouldRejectUnderscoreIdentifierBeforeWordPressProcessesScopedCss(): void
    {
        $selector = '.card';
        $actual = '.card{color:red;}.card_title{color:blue;}';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            CssInterface::M_SELECTOR_IS_OUTSIDE_SCOPE,
            '.card_title',
            '.card',
            '.card_title'
        ));

        $this->makeInstance()->compressed()->parse($actual, $selector);
    }

    public function testItCharacterizesWordPressAppendingUnderscoreAsPartOfTheIdentifier(): void
    {
        $this->assertSame(
            ':root :where(.card_title){color:blue;}',
            $this->processBlocksCustomCssWithWordPress('&_title{color:blue;}', '.card')
        );
    }

    /**
     * @dataProvider scopedAtRuleProvider
     */
    public function testItShouldRejectAtRulesInScopedCss(
        string $actual,
        string $atRuleName
    ): void {
        $selector = '.test-selector';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(\sprintf(
            CssInterface::M_AT_RULES_ARE_NOT_SUPPORTED_IN_SCOPED_CSS,
            $atRuleName
        ));

        $this->makeInstance()->compressed()->parse($actual, $selector);
    }

    public static function scopedAtRuleProvider(): iterable
    {
        yield '@media' => [
            'actual' => '.test-selector{color:red;}@media (min-width: 600px){.test-selector{color:blue;}}',
            'atRuleName' => '@media',
        ];

        yield '@supports' => [
            'actual' => '.test-selector{display:block;}@supports (display: grid){.test-selector{display:grid;}}',
            'atRuleName' => '@supports',
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
