<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Styles;

use ItalyStrap\Tests\CssStyleStringProviderTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Css;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Scss;
use ScssPhp\ScssPhp\Compiler;

class ScssTest extends UnitTestCase
{
    use CssStyleStringProviderTrait {
        CssStyleStringProviderTrait::newStyleProvider as newStyleProviderTrait;
    }

    private function makeInstance(): Scss
    {
        $presets = $this->makePresets();
        return new Scss(new Css($presets), new Compiler(), $presets);
    }

    public function testItShouldBeInstantiable(): void
    {
        $instance = $this->makeInstance();
        $this->assertInstanceOf(Scss::class, $instance);
    }

    public static function newStyleProvider(): iterable
    {
        foreach (self::newStyleProviderTrait() as $key => $value) {
            yield $key => $value;
        }

        yield 'selector used also as prefix for nested selectors' => [
            'selector' => '.test-selector',
            'actual' => <<<CSS
.test-selector {
    gap: 0;

    &.test-selector-one,
    & .test-selector-two {
        color: blue;
    }
}
CSS,
            'expected' => 'gap: 0;&.test-selector-one{color: blue;}& .test-selector-two{color: blue;}',
        ];
    }

    /**
     * @dataProvider newStyleProvider
     */
    public function testItShouldParseWithNewMethod(string $selector, string $actual, string $expected): void
    {
        $this->presets->parse($actual)->willReturn($actual)->shouldBeCalledTimes(1);
        $parseString = $this->makeInstance()->parse($actual, $selector);
        $this->assertSame($expected, $parseString, 'The parsed string is not the same as expected');
    }
}
