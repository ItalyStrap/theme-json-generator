<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Settings\Color\Utilities;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\ColorInfo;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\ColorModifier;

class ColorModifiertTest extends UnitTestCase
{
    protected function makeInstance(string $color): ColorModifier
    {
        return new ColorModifier(new ColorInfo($color));
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

        $this->assertStringMatchesFormat('#cccccc', (string)$sut->tint(0.6), '');
        $this->assertStringMatchesFormat('#333333', (string)$sut->shade(0.6), '');
    }

    public function testItShouldTone(): void
    {
        $sut = $this->makeInstance('#ff0000');

        $this->assertStringMatchesFormat('#c04040', (string)$sut->tone(0.5), '');
    }

    public function testItShouldReturnComplementaryColor(): void
    {
        $sut = $this->makeInstance('#ff0000');

        $this->assertSame('#00ffff', (string)$sut->complementary(), '');
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
