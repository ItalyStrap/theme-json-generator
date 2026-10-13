<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Values;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final class CssColorTest extends UnitTestCase
{
    protected function makeInstance(string $color): CssColor
    {
        return new CssColor($color);
    }

    public static function equivalentColorProvider(): \Generator
    {
        yield 'hex and rgb' => ['#ffc107', 'rgb(255,193,7)'];
        yield 'red hsla' => ['#ff0000', 'hsla(0,100%,50%,1)'];
        yield 'green hsla' => ['#00ff00', 'hsla(120,100%,50%,1)'];
        yield 'blue hsl' => ['#0000ff', 'hsl(240,100%,50%)'];
        yield 'full turn hue' => ['hsl(0,100%,50%)', 'hsl(360,100%,50%)'];
        yield 'hex casing' => ['#FFC107', '#ffc107'];
        yield 'rgb spacing' => ['rgb(255,0,0)', 'rgb(255, 0, 0)'];
        yield 'opaque alpha' => ['#ff0000', 'rgba(255,0,0,1)'];
        yield 'translucent alpha' => ['rgba(255,0,0,0.5)', 'hsla(0,100%,50%,0.5)'];
        yield 'transparent alpha' => ['#00000000', 'rgba(0,0,0,0)'];
        yield 'precise alpha' => ['rgba(255,0,0,0.501)', 'rgba(255, 0, 0, 0.501)'];
    }

    /**
     * @dataProvider equivalentColorProvider
     */
    public function testItShouldCompareEquivalentStringsAndInstances(string $source, string $equivalent): void
    {
        $sut = $this->makeInstance($source);
        $other = $this->makeInstance($equivalent);

        $this->assertTrue($sut->equals($equivalent));
        $this->assertTrue($sut->equals($other));
        $this->assertTrue($other->equals($source));
        $this->assertTrue($other->equals($sut));
        $this->assertTrue($sut->equals($sut));
    }

    public static function differentColorProvider(): \Generator
    {
        yield 'red channel' => ['rgb(1,2,3)', 'rgb(2,2,3)'];
        yield 'green channel' => ['rgb(1,2,3)', 'rgb(1,3,3)'];
        yield 'blue channel' => ['rgb(1,2,3)', 'rgb(1,2,4)'];
        yield 'alpha channel' => ['rgba(255,0,0,0.5)', 'rgba(255,0,0,1)'];
        yield 'alpha below display precision' => ['rgba(255,0,0,0.501)', 'rgba(255,0,0,0.504)'];
        yield 'hex alpha is not exactly half' => ['#ff000080', 'rgba(255,0,0,0.5)'];
        yield 'transparent channels differ' => ['rgba(255,0,0,0)', 'rgba(0,0,255,0)'];
        yield 'rounded yellow hsla' => ['#ffc107', 'hsla(45,100%,51%,1)'];
        yield 'rounded red hsla' => ['#dc3545', 'hsla(354,70%,54%,1)'];
    }

    /**
     * @dataProvider differentColorProvider
     */
    public function testItShouldRejectDifferentStringsAndInstances(string $source, string $different): void
    {
        $sut = $this->makeInstance($source);
        $other = $this->makeInstance($different);

        $this->assertFalse($sut->equals($different));
        $this->assertFalse($sut->equals($other));
        $this->assertFalse($other->equals($source));
        $this->assertFalse($other->equals($sut));
    }

    public function testItShouldCompareAnotherColorInterfaceImplementation(): void
    {
        $rgba = $this->prophesize(CssColorInterface::class);
        $rgba->red()->willReturn(255);
        $rgba->green()->willReturn(0);
        $rgba->blue()->willReturn(0);
        $rgba->alpha()->willReturn(0.501);
        $this->cssColor->alpha()->willReturn(0.501);
        $this->cssColor->toRgba(0.501)->willReturn($rgba->reveal())->shouldBeCalledOnce();

        $sut = $this->makeInstance('rgba(255,0,0,0.501)');

        $this->assertTrue($sut->equals($this->makeCssColor()));
    }

    public function testItShouldPreserveChannelsAcrossConversionsBeforeStringSerialization(): void
    {
        $sut = $this->makeInstance('#ffc107');

        $this->assertTrue($sut->equals($sut->toHsl()));
        $this->assertTrue($sut->equals($sut->toHsla()));
        $this->assertTrue($sut->equals($sut->toHsla()->toRgb()));
        $this->assertTrue($sut->equals($sut->toHsla()->toHex()));
        $this->assertFalse($sut->equals((string)$sut->toHsla()));
    }

    public function testItShouldPreserveAlphaAcrossConversionsBeforeStringSerialization(): void
    {
        $sut = $this->makeInstance('rgba(255,193,7,0.501)');

        $this->assertTrue($sut->equals($sut->toHsla()));
        $this->assertTrue($sut->equals($sut->toHsla()->toRgba()));
        $this->assertFalse($sut->equals((string)$sut->toRgba()));
        $this->assertFalse($sut->equals($sut->toHsla(0.5)));
        $this->assertFalse($sut->equals($sut->toRgb()));
        $this->assertFalse($sut->equals($sut->toHex()));
        $this->assertFalse($sut->equals($sut->toHsl()));
    }

    public static function colorFormatProvider(): \Generator
    {
        yield [
            '#ffc107', // HEX
            'rgb(255,193,7)', // RGB
            'hsla(45,100%,51%,1)', // HSLA
        ];

        yield [
            '#dc3545', // HEX
            'rgb(220,53,69)', // RGB
            'hsla(354,70%,54%,1)', // HSLA
        ];

        yield [
            '#532952', // HEX
            'rgb(83,41,82)', // RGB
            'hsla(301,34%,24%,1)', // HSLA
        ];

        yield [
            '#512952', // HEX
            'rgb(81,41,82)', // RGB
            'hsla(299,33%,24%,1)', // HSLA
        ];

        yield [
            '#faffff', // HEX
            'rgb(250,255,255)', // RGB
            'hsla(180,100%,99%,1)', // HSLA
        ];

        yield [
            '#feffff', // HEX
            'rgb(254,255,255)', // RGB
            'hsla(180,100%,100%,1)', // HSLA
        ];

        yield [
            '#fefeff', // HEX
            'rgb(254,254,255)', // RGB
            'hsla(240,100%,100%,1)', // HSLA
        ];

        yield [
            '#fefefd', // HEX
            'rgb(254,254,253)', // RGB
            'hsla(60,33%,99%,1)', // HSLA
        ];

        yield [
            '#040504', // HEX
            'rgb(4,5,4)', // RGB
            'hsla(120,11%,2%,1)', // HSLA
        ];

        yield [
            '#000000', // HEX
            'rgb(0,0,0)', // RGB
            'hsla(0,0%,0%,1)', // HSLA
        ];

        yield [
            '#808080', // HEX
            'rgb(128,128,128)', // RGB
            'hsla(0,0%,50%,1)', // HSLA
        ];

        yield [
            '#ffffff', // HEX
            'rgb(255,255,255)', // RGB
            'hsla(0,0%,100%,1)', // HSLA
        ];
    }

    /**
     * @dataProvider colorFormatProvider
     */
    public function testItShouldConvertEdgeCase(string $hex, string $rgb, string $hsla): void
    {
        $sut = $this->makeInstance($hex);

        $this->assertStringMatchesFormat($hex, (string) $sut->toHex(), '');
        $this->assertStringMatchesFormat($rgb, (string) $sut->toRgb(), '');
        $this->assertStringMatchesFormat($hsla, (string) $sut->toHsla(), '');

        $this->assertStringMatchesFormat($hex, (string) $sut->toRgb()->toHex(), '');
        $this->assertStringMatchesFormat($hsla, (string) $sut->toRgb()->toHsla(), '');

        $this->assertStringMatchesFormat($rgb, (string) $sut->toHex()->toRgb(), '');
        $this->assertStringMatchesFormat($hsla, (string) $sut->toHex()->toHsla(), '');

        $this->assertStringMatchesFormat($hex, (string) $sut->toHsla()->toHex(), '');
        $this->assertStringMatchesFormat($rgb, (string) $sut->toHsla()->toRgb(), '');
    }

    public static function colorProvider(): \Generator
    {
        yield 'Dark hex' => [
            '#000000', // Color
            '00', // Red
            '00', // Green
            '00', // Blue
        ];

        yield 'Red hex' => [
            '#ff0000', // Color
            'ff', // Red
            '00', // Green
            '00', // Blue
        ];

        yield 'Red rgb' => [
            'rgb(255,0,0)', // Color
            255, // Red
            0, // Green
            0, // Blue
        ];
    }

    /**
     * @dataProvider colorProvider
     */
    public function testItShouldGerRed(string $color, string|int $r, string|int $g, string|int $b): void
    {
        $sut = $this->makeInstance($color);

        $this->assertSame($r, $sut->red(), 'It should be red');
        $this->assertSame($g, $sut->green(), 'It should be green');
        $this->assertSame($b, $sut->blue(), 'It should be blue');
    }

    public static function colorProviderForArray(): \Generator
    {
        yield 'Dark hex' => [
            '#000000', // Color
            [
                '00', // Red
                '00', // Green
                '00', // Blue
            ]
        ];

        yield 'Red hex' => [
            '#ff0000', // Color
            [
                'ff', // Red
                '00', // Green
                '00', // Blue
            ]
        ];

        yield 'Red rgb' => [
            'rgb(255,0,0)', // Color
            [
                255, // Red
                0, // Green
                0, // Blue
            ]
        ];

        yield 'Red hsl' => [
            'hsl(0, 100%, 50%)', // Color
            [
                255, // Red
                0, // Green
                0, // Blue
            ]
        ];
    }

    /**
     * @dataProvider colorProviderForArray
     * @param array{string|int, string|int, string|int} $expected
     */
    public function testItShouldReturnArray(string $color, array $expected): void
    {
        $sut = $this->makeInstance($color);

        $this->assertSame($expected, [
            $sut->red(),
            $sut->green(),
            $sut->blue(),
        ], 'It should be an array');
    }

    public static function darkerColorProvider(): \Generator
    {
        yield 'Black' => [
            '#000000'
        ];

        yield 'Hsl gray1' => [
            'hsl(0, 0%, 50%)'
        ];

        yield 'Hsl gray2' => [
            'hsl(0, 0%, 49%)'
        ];

        yield 'Hsl red' => [
            'hsl(0, 100%, 49%)'
        ];
    }

    /**
     * @dataProvider darkerColorProvider
     */
    public function testItShouldBeDark(string $color): void
    {
        $sut = $this->makeInstance($color);

        $this->assertTrue($sut->isDark());
    }

    public function testItShouldBeDarkOrLight(): void
    {
        $sut = $this->makeInstance('#ffffff');

        $this->assertFalse($sut->isDark());
        $this->assertTrue($sut->isLight());

        $sut = $this->makeInstance('#000000');

        $this->assertFalse($sut->isLight());
    }

    public function testItShouldReturnHex(): void
    {
        $sut = $this->makeInstance('#000000');

        $this->assertStringMatchesFormat('#000000', (string)$sut->toHex(), '');
        $this->assertStringMatchesFormat('rgb(0,0,0)', (string)$sut->toRgb(), '');
        $this->assertStringMatchesFormat('rgba(0,0,0,1.00)', (string)$sut->toRgba(), '');
        $this->assertStringMatchesFormat('hsl(0,0%,0%)', (string)$sut->toHsl(), '');
        $this->assertStringMatchesFormat('hsla(0,0%,0%,1)', (string)$sut->toHsla(), '');
    }

    public function testHueSaturationLightnessAlphaMethods(): void
    {
        $sut = $this->makeInstance('#91bcee');

        $this->assertSame(212, $sut->hue(), '');
        $this->assertSame(73, $sut->saturation(), '');
        $this->assertSame(75, $sut->lightness(), '');
        $this->assertSame('ff', $sut->alpha(), '');
    }

    public function testItShouldReturnLuminanceValue(): void
    {
        $sut = $this->makeInstance('#ff0000');

        $this->assertEqualsWithDelta(0.2126, $sut->luminance(), PHP_FLOAT_EPSILON, '');
    }

    public function testItShouldReturnRelativeLuminanceValue(): void
    {
        $sut = $this->makeInstance('#000000');
        $color = $this->makeInstance('#ffffff');
        $this->assertEqualsWithDelta(21.0, $sut->relativeLuminance($color), PHP_FLOAT_EPSILON, '');

        $sut = $this->makeInstance('#000000');
        $color = $this->makeInstance('#bada55');
        $this->assertGreaterThanOrEqual(4.5, $sut->relativeLuminance($color), '');
    }

    public function testConversionToDifferentFormat(): void
    {
        $sut = $this->makeInstance('rgba(0,0,0,0.25)');
        $this->assertEqualsWithDelta(0.25, $sut->alpha(), PHP_FLOAT_EPSILON, '');

        $sut = $this->makeInstance('#000000');
        $this->assertSame('ff', $sut->alpha(), '');

        $sut = $this->makeInstance('hsla(0,0,0,0.25)');
        $this->assertEqualsWithDelta(0.25, $sut->alpha(), PHP_FLOAT_EPSILON, '');

        $this->assertStringMatchesFormat('#000000', (string)$sut->toHex(), '');
        $this->assertStringMatchesFormat('rgb(0,0,0)', (string)$sut->toRgb(), '');
        $this->assertStringMatchesFormat('rgba(0,0,0,0.25)', (string)$sut->toRgba(), '');
        $this->assertStringMatchesFormat('hsl(0,0%,0%)', (string)$sut->toHsl(), '');
        $this->assertStringMatchesFormat('hsla(0,0%,0%,0.25)', (string)$sut->toHsla(), '');
    }

    public static function hslFullTurnEquivalenceProvider(): \Generator
    {
        yield 'red' => [
            'hsl(0,100%,50%)',
            'hsl(360,100%,50%)',
            '#ff0000',
            'rgb(255,0,0)',
            'rgba(255,0,0,1.00)',
            'hsl(0,100%,50%)',
            'hsla(0,100%,50%,1)',
        ];

        yield 'dark desaturated red' => [
            'hsl(0,50%,25%)',
            'hsl(360,50%,25%)',
            '#602020',
            'rgb(96,32,32)',
            'rgba(96,32,32,1.00)',
            'hsl(0,50%,25%)',
            'hsla(0,50%,25%,1)',
        ];
    }

    /**
     * @dataProvider hslFullTurnEquivalenceProvider
     */
    public function testHslZeroAndFullTurnHueAreEquivalentInConversions(
        string $zeroHue,
        string $fullTurnHue,
        string $hex,
        string $rgb,
        string $rgba,
        string $hsl,
        string $hsla
    ): void {
        $zero = $this->makeInstance($zeroHue);
        $fullTurn = $this->makeInstance($fullTurnHue);

        $this->assertSame($hex, (string)$zero->toHex());
        $this->assertSame($hex, (string)$fullTurn->toHex());
        $this->assertSame($rgb, (string)$zero->toRgb());
        $this->assertSame($rgb, (string)$fullTurn->toRgb());
        $this->assertSame($rgba, (string)$zero->toRgba());
        $this->assertSame($rgba, (string)$fullTurn->toRgba());

        $this->assertSame($hsl, (string)$zero->toHex()->toHsl());
        $this->assertSame($hsl, (string)$fullTurn->toHex()->toHsl());
        $this->assertSame($hsl, (string)$zero->toRgb()->toHsl());
        $this->assertSame($hsl, (string)$fullTurn->toRgb()->toHsl());
        $this->assertSame($hsla, (string)$zero->toRgba()->toHsla());
        $this->assertSame($hsla, (string)$fullTurn->toRgba()->toHsla());
    }
}
