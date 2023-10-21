<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\ColorDataType;

class ColorDataTypeTest extends UnitTestCase
{
    use BaseUnitTrait;

    protected function makeInstance(): ColorDataType
    {
        $sut = new ColorDataType($this->base_color);
        return $sut;
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
     * @test
     */
    public function itShouldGerRed(string $color, $r, $g, $b)
    {
        $this->base_color = $color;
        $sut = $this->makeInstance();

        $this->assertSame($r, $sut->red(), 'It should be red');
        $this->assertSame($g, $sut->green(), 'It should be green');
        $this->assertSame($b, $sut->blue(), 'It should be blue');
    }

    public function colorProviderForArray()
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
     * @test
     */
    public function itShouldReturnArray(string $color, $expected)
    {
        $this->base_color = $color;
        $sut = $this->makeInstance();

        $this->assertSame($expected, $sut->toArray(), 'It should be an array');
    }

    public function darkerColorProvider()
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
     * @test
     */
    public function itShouldBeDark(string $color)
    {
        $this->base_color = $color;
        $sut = $this->makeInstance();

        $this->assertTrue($sut->isDark());
    }

    /**
     * @test
     */
    public function itShouldNotBeDark()
    {
        $this->base_color = '#ffffff';
        $sut = $this->makeInstance();

        $this->assertFalse($sut->isDark());
    }

    /**
     * @test
     */
    public function itShouldBeLight()
    {
        $this->base_color = '#ffffff';
        $sut = $this->makeInstance();

        $this->assertTrue($sut->isLight());
    }

    /**
     * @test
     */
    public function itShouldNotBeLight()
    {
        $this->base_color = '#000000';
        $sut = $this->makeInstance();

        $this->assertFalse($sut->isLight());
    }

    /**
     * @test
     */
    public function itShouldReturnHex()
    {
        $this->base_color = '#000000';
        $sut = $this->makeInstance();

        $this->assertStringMatchesFormat($this->base_color, $sut->toHex(), '');
    }

    /**
     * @test
     */
    public function itShouldReturnRgb()
    {
        $this->base_color = '#000000';
        $sut = $this->makeInstance();

        $this->assertStringMatchesFormat('rgb(0,0,0)', $sut->toRgb(), '');
    }

    /**
     * @test
     */
    public function itShouldReturnRgba()
    {
        $this->base_color = '#000000';
        $sut = $this->makeInstance();

        $this->assertStringMatchesFormat('rgba(0,0,0,1.00)', $sut->toRgba(), '');
    }

    /**
     * @test
     */
    public function itShouldReturnHsl()
    {
        $this->base_color = '#000000';
        $sut = $this->makeInstance();

        $this->assertStringMatchesFormat('hsl(0,0%,0%)', $sut->toHsl(), '');
    }

    /**
     * @test
     */
    public function itShouldReturnHsla()
    {
        $this->base_color = '#000000';
        $sut = $this->makeInstance();

        $this->assertStringMatchesFormat('hsla(0,0%,0%,1)', $sut->toHsla(), '');
    }

    /**
     * @test
     */
    public function itShouldReturnDarkenColor()
    {
        $this->base_color = '#ffffff';
        $sut = $this->makeInstance();

        $this->assertStringMatchesFormat('#cccccc', $sut->darken(20)->toHex(), '');
    }

    /**
     * @test
     */
    public function itShouldReturnLightenColor()
    {
        $this->base_color = '#000000';
        $sut = $this->makeInstance();

        $this->assertStringMatchesFormat('#333333', $sut->lighten(20)->toHex(), '');
    }

    public function weightProvider()
    {

        yield '1 white' => [
            // weight
            1,
            // Expected
            '#ffffff',
            // Mixed with
            '#ffffff'
        ];

        yield '0.8 white' => [
            // weight
            0.8,
            // Expected
            '#e5e5e5',
            // Mixed with
            '#ffffff'
        ];

        yield '0.6 white' => [
            // weight
            0.6,
            // Expected
            '#cccccc',
            // Mixed with
            '#ffffff'
        ];

        yield '0.4 white' => [
            // weight
            0.4,
            // Expected
            '#b2b2b2',
            // Mixed with
            '#ffffff'
        ];

        yield '0.2 white' => [
            // weight
            0.2,
            // Expected
            '#999999',
            // Mixed with
            '#ffffff'
        ];

        yield '0 white' => [
            // weight
            0,
            // Expected
            '#7f7f7f',
            // Mixed with
            '#ffffff'
        ];

        yield '1 black' => [
            // weight
            1,
            // Expected
            '#000000',
            // Mixed with
            '#000000'
        ];

        yield '0.8 black' => [
            // weight
            0.8,
            // Expected
            '#191919',
            // Mixed with
            '#000000'
        ];

        yield '0.6 black' => [
            // weight
            0.6,
            // Expected
            '#333333',
            // Mixed with
            '#000000'
        ];

        yield '0.4 black' => [
            // weight
            0.4,
            // Expected
            '#4c4c4c',
            // Mixed with
            '#000000'
        ];

        yield '0.2 black' => [
            // weight
            0.2,
            // Expected
            '#666666',
            // Mixed with
            '#000000'
        ];

        yield '0 black' => [
            // weight
            0,
            // Expected
            '#7f7f7f',
            // Mixed with
            '#000000'
        ];
    }

    /**
     * @dataProvider weightProvider
     * @test
     */
    public function itShouldReturnMixedColorByWeight(float $weight, string $expected, string $mixedWith)
    {

        $this->base_color = '#7f7f7f';
        $sut = $this->makeInstance();

        $this->assertStringMatchesFormat($expected, $sut->mix($mixedWith, $weight)->toHex(), '');

//      codecept_debug( $sut->toHex() );
//      codecept_debug( $sut->mix( $this->base_color, 0.2  )->toHex() );
    }

    /**
     * @test
     */
    public function itShouldTintAndShade()
    {

        $this->base_color = '#7f7f7f';
        $sut = $this->makeInstance();

        $this->assertStringMatchesFormat('#cccccc', $sut->tint(0.6)->toHex(), '');
        $this->assertStringMatchesFormat('#333333', $sut->shade(0.6)->toHex(), '');
    }

    /**
     * @test
     */
    public function itShouldTone()
    {

        $this->base_color = '#ff0000';
        $sut = $this->makeInstance();

        $this->assertStringMatchesFormat('#c04040', $sut->tone(0.5)->toHex(), '');
    }

    /**
     * @test
     */
    public function itShouldReturnComplementaryColor()
    {
        $this->base_color = '#ff0000';
        $sut = $this->makeInstance();

        $this->assertStringMatchesFormat('#00ffff', $sut->complementary()->toHex(), '');
    }

    /**
     * @test
     */
    public function itShouldReturnLuminanceValue()
    {
        $this->base_color = '#ff0000';
        $sut = $this->makeInstance();

        $this->assertSame(0.2126, $sut->luminance(), '');
    }

    /**
     * @test
     */
    public function itShouldReturnRelativeLuminanceValue()
    {
        $this->base_color = '#000000';
        $sut = $this->makeInstance();

        $this->base_color = '#ffffff';
        $color = $this->makeInstance();

        $this->assertSame(21.0, $sut->relativeLuminance($color), '');
    }

    /**
     * @test
     */
    public function itShouldReturnRelativeLuminanceValue2()
    {
        $this->base_color = '#000000';
        $sut = $this->makeInstance();

        $this->base_color = '#bada55';
        $color = $this->makeInstance();

//      codecept_debug( $sut->relativeLuminance( $color ) );
        $this->assertTrue($sut->relativeLuminance($color) >= 4.5, '');
    }
}
