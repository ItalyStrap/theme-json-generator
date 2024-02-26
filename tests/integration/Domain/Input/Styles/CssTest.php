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
        styleProvider as styleProviderTrait;
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
}
