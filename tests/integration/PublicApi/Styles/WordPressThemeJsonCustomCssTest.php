<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Integration\PublicApi\Styles;

use ItalyStrap\Tests\IntegrationTestCase;

final class WordPressThemeJsonCustomCssTest extends IntegrationTestCase
{
    /**
     * @dataProvider unsupportedAtRuleProvider
     */
    public function testItCharacterizesWordPressDoesNotPreserveAtRulesInBlockCustomCss(
        string $css,
        string $expectedProcessedCss,
        string $expectedPreservedAtRuleCss
    ): void {
        $processedCss = $this->processBlocksCustomCssWithWordPress($css, '.test-selector');

        $this->assertSame($expectedProcessedCss, $processedCss);
        $this->assertNotSame($expectedPreservedAtRuleCss, $processedCss);
    }

    public static function unsupportedAtRuleProvider(): iterable
    {
        yield '@media' => [
            'css' => '@media (min-width: 600px){color: blue;}',
            'expectedProcessedCss' => ':root :where(.test-selector@media (min-width: 600px)){color: blue;}',
            'expectedPreservedAtRuleCss' => '@media (min-width: 600px){:root :where(.test-selector){color: blue;}}',
        ];

        yield '@supports' => [
            'css' => '@supports (display: grid){display: grid;}',
            'expectedProcessedCss' => ':root :where(.test-selector@supports (display: grid)){display: grid;}',
            'expectedPreservedAtRuleCss' => '@supports (display: grid){:root :where(.test-selector){display: grid;}}',
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
