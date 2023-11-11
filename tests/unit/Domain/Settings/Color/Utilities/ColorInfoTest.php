<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Settings\Color\Utilities;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities\ColorInfo;

class ColorInfoTest extends UnitTestCase
{
    protected function makeInstance(string $color): ColorInfo
    {
        return new ColorInfo($color);
    }

    public function colorFormatProvider(): \Generator
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
    public function testItShouldConvertEdgeCase($hex, $rgb, $hsla)
    {
        $sut = $this->makeInstance($hex);

        $this->assertStringMatchesFormat($hex, (string) $sut->toHex(), '');
        $this->assertStringMatchesFormat($rgb, (string) $sut->toRgb(), '');
        $this->assertStringMatchesFormat($hsla, (string) $sut->toHsla(), '');

        $this->assertStringMatchesFormat($hex, (string) $sut->toRgb()->toHex(), '');
        $this->assertStringMatchesFormat($hsla, (string) $sut->toRgb()->toHsla(), '');

        $this->assertStringMatchesFormat($rgb, (string) $sut->toHex()->toRgb(), '');
        $this->assertStringMatchesFormat($hsla, (string) $sut->toHex()->toHsla(), '');

//      $this->assertStringMatchesFormat( $hex, (string) $sut->toHsla()->toHex(), '' );
//      $this->assertStringMatchesFormat( $rgb, (string) $sut->toHsla()->toRgb(), '' );
    }

    public function colorProvider()
    {
        yield 'Dark hex'    => [
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
    public function testItShouldGerRed(string $color, $r, $g, $b): void
    {
        $sut = $this->makeInstance($color);

        $this->assertSame($r, $sut->red(), 'It should be red');
        $this->assertSame($g, $sut->green(), 'It should be green');
        $this->assertSame($b, $sut->blue(), 'It should be blue');
    }

    public function colorProviderForArray(): \Generator
    {
        yield 'Dark hex'    => [
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
    }

    /**
     * @dataProvider colorProviderForArray
     */
    public function testItShouldReturnArray(string $color, $expected)
    {
        $sut = $this->makeInstance($color);

        $this->assertSame($expected, $sut->toArray(), 'It should be an array');
    }

    public function darkerColorProvider(): \Generator
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
    public function testItShouldBeDark(string $color)
    {
        $sut = $this->makeInstance($color);

        $this->assertTrue($sut->isDark());
    }

    public function testItShouldDarkOrLight(): void
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

        $this->assertStringMatchesFormat($this->base_color, (string)$sut->toHex(), '');
    }

    public function testTtShouldReturnRgb()
    {
        $sut = $this->makeInstance('#000000');

        $this->assertStringMatchesFormat('rgb(0,0,0)', (string)$sut->toRgb(), '');
    }

    public function testItShouldReturnRgba()
    {
        $sut = $this->makeInstance('#000000');

        $this->assertStringMatchesFormat('rgba(0,0,0,1.00)', (string)$sut->toRgba(), '');
    }

    public function testItShouldReturnHsl()
    {
        $sut = $this->makeInstance('#000000');

        $this->assertStringMatchesFormat('hsl(0,0%,0%)', (string)$sut->toHsl(), '');
    }

    public function testItShouldReturnHsla()
    {
        $sut = $this->makeInstance('#000000');

        $this->assertStringMatchesFormat('hsla(0,0%,0%,1)', (string)$sut->toHsla(), '');
    }

    public function testItShouldReturnLuminanceValue()
    {
        $this->base_color = '#ff0000';
        $sut = $this->makeInstance('#ff0000');

        $this->assertSame(0.2126, $sut->luminance(), '');
    }

    public function testItShouldReturnRelativeLuminanceValue()
    {
        $sut = $this->makeInstance('#000000');

        $color = $this->makeInstance('#ffffff');

        $this->assertSame(21.0, $sut->relativeLuminance($color), '');
    }

    public function testItShouldReturnRelativeLuminanceValue2()
    {
        $sut = $this->makeInstance('#000000');

        $color = $this->makeInstance('#bada55');

        $this->assertTrue($sut->relativeLuminance($color) >= 4.5, '');
    }
}
