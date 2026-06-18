<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Integration\PublicApi\Styles;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\IntegrationTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;
use Sabberworm\CSS\Parser;

final class WordPressThemeJsonCustomCssTest extends IntegrationTestCase
{
    public function testItCharacterizesGlobalCustomCssInThemeJsonStylesheet(): void
    {
        $css = 'body {color:red}a{color:blue;}';
        $stylesheet = $this->getStylesheetFromThemeJson([
            'version' => 3,
            'styles' => [
                'css' => $css,
            ],
        ], ['custom-css']);

        $this->assertSame($css, $stylesheet);
    }

    public function testItCharacterizesBlockCustomCssWithSelectorReplacementInThemeJsonStylesheet(): void
    {
        $stylesheet = $this->getStylesheetFromThemeJson([
            'version' => 3,
            'styles' => [
                'blocks' => [
                    'core/button' => [
                        'css' => 'color:red;& a{color:blue;}',
                    ],
                ],
            ],
        ]);

        $this->assertStringContainsString(
            ':root :where(.wp-block-button .wp-block-button__link){color:red;}',
            $stylesheet
        );
        $this->assertStringContainsString(
            ':root :where(.wp-block-button .wp-block-button__link a){color:blue;}',
            $stylesheet
        );
    }

    /**
     * WordPress merges declarations into the block selector when nested CSS omits the required "&".
     * The malformed output is asserted intentionally to detect future changes in WordPress behavior.
     */
    public function testItCharacterizesBlockCustomCssWithoutSelectorReplacementInThemeJsonStylesheet(): void
    {
        $stylesheet = $this->getStylesheetFromThemeJson([
            'version' => 3,
            'styles' => [
                'blocks' => [
                    'core/button' => [
                        'css' => 'color:red;a{color:blue;}',
                    ],
                ],
            ],
        ]);

        $this->assertStringContainsString(
            ':root :where(.wp-block-button .wp-block-button__linkcolor:red;a){color:blue;}',
            $stylesheet
        );
    }

    public function testItCharacterizesBlockCustomCssProcessing(): void
    {
        $this->assertSame(
            ':root :where(.wp-block-button){color:red;}:root :where(.wp-block-button a){color:blue;}',
            $this->processBlocksCustomCssWithWordPress('color:red;& a{color:blue;}', '.wp-block-button')
        );

        // Missing "&" makes WordPress concatenate declarations with the selector, producing malformed CSS.
        $this->assertSame(
            ':root :where(.wp-block-buttoncolor:red;a){color:blue;}',
            $this->processBlocksCustomCssWithWordPress('color:red;a{color:blue;}', '.wp-block-button')
        );
    }

    public function testItGeneratesValidGlobalAndScopedCssThroughWordPress(): void
    {
        $config = new Config();
        $themeJson = new ThemeJson($config, new Presets());
        $themeJson->version(3);

        $themeJson->styles()->css('body {color:red}');
        $themeJson->styles()->appendCss('a{color:blue;}');
        $themeJson->styles()->blocks('core/button')->css('color:red');
        $themeJson->styles()->blocks('core/button')->appendCss('& a{color:blue;}');

        $globalCss = $this->getStylesheetFromThemeJson($config->toArray(), ['custom-css']);
        $scopedCss = $this->getStylesheetFromThemeJson($config->toArray());

        $this->assertSame('body {color:red}a{color:blue;}', $globalCss);
        $this->assertStringContainsString(
            ':root :where(.wp-block-button .wp-block-button__link){color:red;}',
            $scopedCss
        );
        $this->assertStringContainsString(
            ':root :where(.wp-block-button .wp-block-button__link a){color:blue;}',
            $scopedCss
        );
        $this->assertNotSame([], (new Parser($globalCss))->parse()->getContents());
        $this->assertNotSame([], (new Parser($scopedCss))->parse()->getContents());
    }

    /**
     * WordPress treats unsupported at-rules as selector text and emits malformed CSS.
     * These expectations characterize that behavior so a future WordPress fix is detected.
     *
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

    /**
     * @param array<string, mixed> $themeJson
     */
    /**
     * @param list<string> $types
     */
    private function getStylesheetFromThemeJson(array $themeJson, array $types = ['styles']): string
    {
        $wpThemeJson = new \WP_Theme_JSON($themeJson);
        $result = $wpThemeJson->get_stylesheet($types);
        $this->assertIsString($result);

        return $result;
    }
}
