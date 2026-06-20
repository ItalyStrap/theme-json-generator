<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Utilities;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\Color;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\ColorModifier;

final class ColorModifierTest extends UnitTestCase
{
    protected function makeInstance(string $color): ColorModifier
    {
        return new ColorModifier(new Color($color));
    }

    public static function typesProvider(): \Iterator
    {
        yield 'Hex white' => [
            '#ffffff',
            '#ffffff',
            '#000000', // Inverted color used once
        ];

        yield 'Hex black' => [
            '#000000',
            '#000000',
            '#ffffff', // Inverted color used once
        ];

        yield 'Rgb white' => [
            'rgb(255,255,255)',
            'rgb(255,255,255)',
            'rgb(0,0,0)', // Inverted color used once
        ];

        yield 'Rgb black' => [
            'rgb(0,0,0)',
            'rgb(0,0,0)',
            'rgb(255,255,255)', // Inverted color used once
        ];

        yield 'Rgba white' => [
            'rgba(255,255,255,1.00)',
            'rgba(255,255,255,1.00)',
            'rgba(0,0,0,1.00)', // Inverted color used once
        ];

        yield 'Rgba black' => [
            'rgba(0,0,0,1.00)',
            'rgba(0,0,0,1.00)',
            'rgba(255,255,255,1.00)', // Inverted color used once
        ];

        yield 'Hsl white' => [
            'hsl(0,0%,100%)',
            'hsl(0,0%,100%)',
            'hsl(0,0%,0%)', // Inverted color used once
        ];

        yield 'Hsl black' => [
            'hsl(0,0%,0%)',
            'hsl(0,0%,0%)',
            'hsl(0,0%,100%)', // Inverted color used once
        ];

        yield 'Hsla white' => [
            'hsla(0,0%,100%,1)',
            'hsla(0,0%,100%,1)',
            'hsla(0,0%,0%,1)', // Inverted color used once
        ];

        yield 'Hsla black' => [
            'hsla(0,0%,0%,1)',
            'hsla(0,0%,0%,1)',
            'hsla(0,0%,100%,1)', // Inverted color used once
        ];
    }

    /**
     * @dataProvider typesProvider
     * To remember:
     * The inverted color is the only one value that is different from the others
     * As you can see the others methods are called withouth arguments, so we can
     * test them and be sure that they return the same type of the original color
     * But the invert method always return the inverted color, and we need to provide it.
     */
    public function testItShouldReturnColorWithSameType(string $color, string $complementary, string $inverted): void
    {
        $sut = $this->makeInstance($color);

        $this->assertSame($color, (string)$sut->tint(), '');
        $this->assertSame($color, (string)$sut->shade(), '');
        $this->assertSame($color, (string)$sut->tone(), '');
        $this->assertSame($color, (string)$sut->opacity(), '');
        $this->assertSame($color, (string)$sut->darken(), '');
        $this->assertSame($color, (string)$sut->lighten(), '');
        $this->assertSame($color, (string)$sut->saturate(), '');
        $this->assertSame($color, (string)$sut->contrast(), '');
        $this->assertSame($complementary, (string)$sut->complementary(), '');
        $this->assertSame($inverted, (string)$sut->invert(), '');
        $this->assertSame($color, (string)$sut->hueRotate(), '');
    }

    public static function greysProvider(): \Iterator
    {
        yield 'Hex grey' => [
            '#7f7f7f',
        ];

        yield 'Hex Light grey' => [
            '#b2b2b2',
        ];

        yield 'Hex Dark grey' => [
            '#4c4c4c',
        ];

        yield 'Rgb grey' => [
            'rgb(127,127,127)',
        ];

        yield 'Rgba grey' => [
            'rgba(127,127,127,1.00)',
        ];

        yield 'Hsl grey' => [
            'hsl(0,0%,50%)',
        ];

        yield 'Hsla grey' => [
            'hsla(0,0%,50%,1)',
        ];
    }

    /**
     * @dataProvider greysProvider
     */
    public function testItShouldReturnTheSameGreyColorCallingComplementary(string $color): void
    {
        $sut = $this->makeInstance($color);

        $this->assertSame($color, (string)$sut->complementary(), '');
    }

    public function testItShouldReturnDarkenColor(): void
    {
        $sut = $this->makeInstance('#ffffff');

        $this->assertSame('#cccccc', (string)$sut->darken(20)->toHex(), '');
    }

    public function testItShouldReturnLightenColor(): void
    {
        $sut = $this->makeInstance('#000000');

        $this->assertStringMatchesFormat('#333333', (string)$sut->lighten(20)->toHex(), '');
    }

    public function testItShouldTintAndShade(): void
    {
        $sut = $this->makeInstance('#7f7f7f');

        $this->assertStringMatchesFormat('#cccccc', (string)$sut->tint(60), '');
        $this->assertStringMatchesFormat('#333333', (string)$sut->shade(60), '');
    }

    public function testItShouldClampMixWeightsToTheSupportedRange(): void
    {
        $sut = $this->makeInstance('#336699');

        $this->assertSame('#ffffff', (string)$sut->tint(150)->toHex());
        $this->assertSame('#336699', (string)$sut->shade(-20)->toHex());
    }

    public function testItShouldAcceptFractionalPercentagesForMixWeights(): void
    {
        $sut = $this->makeInstance('#000000');

        $this->assertSame('#040404', (string)$sut->tint(1.5)->toHex());
    }

    public function testItShouldIncreaseContrastWithoutChangingSaturation(): void
    {
        $sut = $this->makeInstance('hsl(210,50%,40%)');

        $result = $sut->contrast(20);

        $this->assertSame(50, $result->saturation());
        $this->assertLessThan(40, $result->lightness());
    }

    public function testItShouldRoundFractionalModifierResults(): void
    {
        $sut = $this->makeInstance('hsl(210,50%,40%)');

        $this->assertSame('hsl(210,50%,41%)', (string)$sut->lighten(0.6));
    }

    public function testItShouldTone(): void
    {
        $sut = $this->makeInstance('#ff0000');

        $this->assertStringMatchesFormat('#c04040', (string)$sut->tone(50), '');
    }

    public static function alphaPreservingLightnessProvider(): \Generator
    {
        yield 'darken' => [
            'darken',
            'hsla(120,50%,50%,0.5)',
            10,
            'hsla(120,50%,40%,0.5)',
        ];

        yield 'lighten' => [
            'lighten',
            'hsla(120,50%,50%,0.5)',
            10,
            'hsla(120,50%,60%,0.5)',
        ];
    }

    /**
     * @dataProvider alphaPreservingLightnessProvider
     */
    public function testItShouldPreserveAlphaWhenChangingLightness(
        string $method,
        string $color,
        int $amount,
        string $expected
    ): void {
        $sut = $this->makeInstance($color);

        $this->assertSame($expected, (string)$sut->$method($amount));
    }

    public static function alphaPreservingMixProvider(): \Generator
    {
        yield 'tint' => [
            'tint',
            'rgba(100,100,100,0.50)',
            50,
            'rgba(178,178,178,0.50)',
        ];

        yield 'shade' => [
            'shade',
            'rgba(100,100,100,0.50)',
            50,
            'rgba(50,50,50,0.50)',
        ];

        yield 'tone' => [
            'tone',
            'rgba(100,100,100,0.50)',
            50,
            'rgba(114,114,114,0.50)',
        ];
    }

    /**
     * @dataProvider alphaPreservingMixProvider
     */
    public function testItShouldPreserveAlphaWhenMixingColors(
        string $method,
        string $color,
        float $weight,
        string $expected
    ): void {
        $sut = $this->makeInstance($color);

        $this->assertSame($expected, (string)$sut->$method($weight));
    }

    public static function opacityProvider(): \Generator
    {
        yield 'hex without alpha' => [
            '#336699',
            0.5,
            'rgba(51,102,153,0.50)',
        ];

        yield 'rgb without alpha' => [
            'rgb(51,102,153)',
            0.5,
            'rgba(51,102,153,0.50)',
        ];

        yield 'hsl without alpha' => [
            'hsl(210,50%,40%)',
            0.5,
            'hsla(210,50%,40%,0.5)',
        ];

        yield 'rgba with fractional alpha' => [
            'rgba(255,0,0,1.00)',
            0.5,
            'rgba(255,0,0,0.50)',
        ];

        yield 'hsla with fractional alpha' => [
            'hsla(30,100%,50%,1)',
            0.5,
            'hsla(30,100%,50%,0.5)',
        ];
    }

    /**
     * @dataProvider opacityProvider
     */
    public function testItShouldPreserveFractionalAlphaWhenChangingOpacity(
        string $color,
        float $alpha,
        string $expected
    ): void {
        $sut = $this->makeInstance($color);

        $this->assertSame($expected, (string)$sut->opacity($alpha));
    }

    public static function largeNegativeHueRotationProvider(): \Generator
    {
        yield 'minus 400 degrees' => [-400, 'hsl(330,50%,50%)'];
        yield 'minus 760 degrees' => [-760, 'hsl(330,50%,50%)'];
    }

    /**
     * @dataProvider largeNegativeHueRotationProvider
     */
    public function testItShouldHueRotateWithLargeNegativeAmounts(int $amount, string $expected): void
    {
        $sut = $this->makeInstance('hsl(10,50%,50%)');

        $this->assertSame($expected, (string)$sut->hueRotate($amount));
    }

    public static function eightDigitHexModifierProvider(): \Generator
    {
        yield 'darken numeric alpha' => ['#33669980', 'darken', 10, '#264d73'];
        yield 'lighten numeric alpha' => ['#33669980', 'lighten', 10, '#4080bf'];
        yield 'saturate numeric alpha' => ['#33669980', 'saturate', 10, '#2966a3'];
        yield 'contrast numeric alpha' => ['#33669980', 'contrast', 10, '#264d73'];
        yield 'hue rotate numeric alpha' => ['#33669980', 'hueRotate', 10, '#335599'];
        yield 'darken alpha with letters' => ['#336699ab', 'darken', 10, '#264d73'];
        yield 'invert alpha with letters' => ['#336699ab', 'invert', null, '#6699cc'];
        yield 'tint alpha with letters' => ['#336699ab', 'tint', 50, '#99b3cc'];
    }

    /**
     * @dataProvider eightDigitHexModifierProvider
     */
    public function testItShouldModifyEightDigitHexColors(
        string $color,
        string $method,
        int|float|null $amount,
        string $expected
    ): void {
        $sut = $this->makeInstance($color);

        $actual = $amount === null ? $sut->$method() : $sut->$method($amount);

        $this->assertSame($expected, (string)$actual);
    }

    public static function complementaryColorProvider(): \Generator
    {
        yield 'red hex' => [
            '#ff0000',
            '#00ffff',
        ];

        yield 'non-zero hsl hue' => [
            'hsl(30,100%,50%)',
            'hsl(210,100%,50%)',
        ];

        yield 'hsl hue in first quadrant' => [
            'hsl(90,100%,50%)',
            'hsl(270,100%,50%)',
        ];

        yield 'hsl hue in second quadrant' => [
            'hsl(180,100%,50%)',
            'hsl(0,100%,50%)',
        ];

        yield 'hsl hue in third quadrant' => [
            'hsl(270,100%,50%)',
            'hsl(90,100%,50%)',
        ];

        yield 'hsl hue in fourth quadrant' => [
            'hsl(330,100%,50%)',
            'hsl(150,100%,50%)',
        ];
    }

    /**
     * @dataProvider complementaryColorProvider
     */
    public function testItShouldReturnComplementaryColor(string $color, string $expected): void
    {
        $sut = $this->makeInstance($color);

        $this->assertSame($expected, (string)$sut->complementary(), '');
    }

    public static function weightProvider(): \Generator
    {
        yield '1 white' => [
            1, // weight
            '#ffffff', // Expected
            '#ffffff', // Mixed with
        ];

        yield '0.8 white' => [
            0.8, // weight
            '#e5e5e5', // Expected
            '#ffffff', // Mixed with
        ];

        yield '0.6 white' => [
            0.6, // weight
            '#cccccc', // Expected
            '#ffffff', // Mixed with
        ];

        yield '0.4 white' => [
            0.4, // weight
            '#b2b2b2',  // Expected
            '#ffffff',  // Mixed with
        ];

        yield '0.2 white' => [
            0.2,    // weight
            '#999999',  // Expected
            '#ffffff',  // Mixed with
        ];

        yield '0 white' => [
            0,  // weight
            '#7f7f7f',  // Expected
            '#ffffff',  // Mixed with
        ];

        yield '1 black' => [
            1,  // weight
            '#000000',  // Expected
            '#000000',  // Mixed with
        ];

        yield '0.8 black' => [
            0.8,    // weight
            '#191919',  // Expected
            '#000000',  // Mixed with
        ];

        yield '0.6 black' => [
            0.6,    // weight
            '#333333',  // Expected
            '#000000',  // Mixed with
        ];

        yield '0.4 black' => [
            0.4,    // weight
            '#4c4c4c',  // Expected
            '#000000',  // Mixed with
        ];

        yield '0.2 black' => [
            0.2,    // weight
            '#666666',  // Expected
            '#000000',  // Mixed with
        ];

        yield '0 black' => [
            0,  // weight
            '#7f7f7f',  // Expected
            '#000000',  // Mixed with
        ];
    }

    /**
     * @dataProvider weightProvider
     */
    public function testItShouldReturnMixedColorByWeight(float $weight, string $expected, string $mixedWith): void
    {
  //      $this->markTestSkipped('This test is skipped because it is not implemented yet');
  //      $sut = $this->makeInstance('#7f7f7f');
  //      $this->assertSame($expected, $sut->mix($mixedWith, $weight)->toHex(), '');
    }

    public function testItShouldNotThrowException(): void
    {
        $sut = $this->makeInstance('#3986E0');
        $color = (string)$sut->darken(20);
        $color = (string)$sut->lighten(20);
        $color = (string)$sut->darken(20)->toHsla();
        $color = (string)$sut->darken(20)->toHsla();
    }

    public function testShadeVsTintVsLightenVsDarken(): void
    {
        $sut = $this->makeInstance('hsla(199,100%,73%,1)');

        $this->assertSame(
            'hsla(199,100%,73%,1)',
            (string)$sut->color(),
            'The color should be hsla(199,100%,73%,1)'
        );

        $this->assertSame(
            'hsla(199,100%,78%,1)',
            (string)$sut->tint(20),
            'The color should be hsla(199,100%,78%,1)'
        );


        $this->assertSame(
            'hsla(199,52%,58%,1)',
            (string)$sut->shade(20),
            'The color should be hsla(199,52%,58%,1)'
        );

        $this->assertSame(
            'hsla(199,100%,93%,1)',
            (string)$sut->lighten(20),
            'The color should be hsla(199,100%,93%,1)'
        );

        $this->assertSame(
            'hsla(199,100%,53%,1)',
            (string)$sut->darken(20),
            'The color should be hsla(199,100%,53%,1)'
        );
    }
}
