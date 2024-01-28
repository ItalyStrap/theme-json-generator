<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Integration\Domain\Input\Styles;

use ItalyStrap\Tests\IntegrationTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Css;

class CssTest extends IntegrationTestCase
{
    use ProcessBlocksCustomCssTrait;

    private function makeInstance(): Css
    {
        return new Css();
    }

    public static function styleProvider(): iterable
    {
        yield 'root with pseudo elements' => [
            'selector' => '.test-selector',
            'actual' => 'height: 100%;width: 100%;color: red;:hover {color: red;}::placeholder {color: red;}',
            // phpcs:disable
            'expected' => '.test-selector{height: 100%;width: 100%;color: red;}.test-selector:hover {color: red;}.test-selector::placeholder {color: red;}',
            // phpcs:enable
        ];

        yield 'with nested selector' => [
            'selector' => '.test-selector',
            'actual' => 'color: red; margin: auto; .one{color: blue;} .two{color: green;}',
            // phpcs:disable
            'expected' => '.test-selector{color: red; margin: auto;}.test-selector .one{color: blue;}.test-selector .two{color: green;}',
            // phpcs:enable
        ];

        /**
         * @todo This test is not working because of the specificity of the selector
         * phpcs:disable
         */
//        yield 'with multiple root selectors' => [
//            'selector' => '.foo, .bar',
//            'actual' => 'color: red; margin: auto; .one{color: blue;} .two{color: green;} ::before{color: yellow;} ::before{color: purple;} .three::before{color: orange;} .four::before{color: skyblue;}',
//            'expected' => '.foo, .bar{color: red; margin: auto;}.foo.one, .bar.one{color: blue;}.foo .two, .bar .two{color: green;}.foo::before, .bar::before{color: yellow;}.foo ::before, .bar ::before{color: purple;}.foo.three::before, .bar.three::before{color: orange;}.foo .four::before, .bar .four::before{color: skyblue;}',
//        ];
        // phpcs:enable
    }

    /**
     * @dataProvider styleProvider
     */
    public function testItProcessInRealScenario(string $selector, string $actual, string $expected): void
    {
        $parsed = $this->makeInstance()->parseString($actual);

        $result = $this->process_blocks_custom_css(
            $parsed,
            $selector
        );

        $this->assertSame($expected, $result);
    }
}
