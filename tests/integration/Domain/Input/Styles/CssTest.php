<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Integration\Domain\Input\Styles;

use ItalyStrap\Tests\CssStyleStringProviderTrait;
use ItalyStrap\Tests\IntegrationTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Css;

class CssTest extends IntegrationTestCase
{
    use ProcessBlocksCustomCssTrait;
    use CssStyleStringProviderTrait {
        CssStyleStringProviderTrait::styleProvider as styleProviderTrait;
        CssStyleStringProviderTrait::newStyleProvider as newStyleProviderTrait;
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
    }

    /**
     * @dataProvider styleProvider
     */
    public function testItProcessInRealScenario(string $selector, string $actual, string $expected): void
    {
        $parseString = $this->makeInstance()->parseString($actual, $selector);
        $this->assertSame($expected, $parseString, 'The parsed string is not the same as expected');


        $result = $this->process_blocks_custom_css(
            $parseString,
            $selector
        );

        $this->assertSame($actual, $result, 'The result string is not the same as original');
    }

    public static function newStyleProvider(): iterable
    {
//        foreach (self::newStyleProviderTrait() as $key => $value) {
//            yield $key => $value;
//        }

        yield 'root custom properties mixed with css' => [
            // phpcs:disable
            'selector' => '.test-selector',
            'actual' => '.test-selector{--foo: 100%;--bar: 100%;}.test-selector #firstParagraph{background-color: var(--first-color);color: var(--second-color);}.test-selector .foo{--bar: 50%;color: red;width: var(--foo);height: var(--bar);}',
            'expected' => '--foo: 100%;--bar: 100%;& #firstParagraph{background-color: var(--first-color);color: var(--second-color);}& .foo{--bar: 50%;color: red;width: var(--foo);height: var(--bar);}',
            // phpcs:enable
        ];
    }

    /**
     * @dataProvider newStyleProvider
     */
    public function testNewItProcessInRealScenario(string $selector, string $actual, string $expected): void
    {
        $parseString = $this->makeInstance()->parseString($actual, $selector);
        $this->assertSame($expected, $parseString, 'The parsed string is not the same as expected');


        $result = $this->process_blocks_custom_css(
            $parseString,
            $selector
        );

        $this->assertSame($actual, $result, 'The result string is not the same as original');
    }
}
