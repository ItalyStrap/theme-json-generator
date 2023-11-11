<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Settings\Color\Utilities;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities\ColorModifier;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities\ColorInfo;

class ColorModifiertTest extends UnitTestCase
{
    protected function makeInstance(string $color): ColorModifier
    {
        return new ColorModifier(new ColorInfo($color));
    }

    public function testItShouldReturnDarkenColor()
    {
        $sut = $this->makeInstance('#ffffff');

        $this->assertSame('#cccccc', (string)$sut->darken(20)->toHex(), '');
    }

    public function testItShouldReturnLightenColor()
    {
        $sut = $this->makeInstance('#000000');

        $this->assertStringMatchesFormat('#333333', (string)$sut->lighten(20)->toHex(), '');
    }

    public function weightProvider(): \Generator
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
     * @test
     */
//  public function itShouldReturnMixedColorByWeight(float $weight, string $expected, string $mixedWith)
//  {
//      $this->markTestSkipped('This test is skipped because it is not implemented yet');
//      $sut = $this->makeInstance('#7f7f7f');
//      $this->assertSame($expected, $sut->mix($mixedWith, $weight)->toHex(), '');
//  }

    public function testItShouldTintAndShade()
    {
        $sut = $this->makeInstance('#7f7f7f');

        $this->assertStringMatchesFormat('#cccccc', (string)$sut->tint(0.6)->toHex(), '');
        $this->assertStringMatchesFormat('#333333', (string)$sut->shade(0.6)->toHex(), '');
    }

    public function testItShouldTone()
    {
        $sut = $this->makeInstance('#ff0000');

        $this->assertStringMatchesFormat('#c04040', (string)$sut->tone(0.5)->toHex(), '');
    }

    public function testItShouldReturnComplementaryColor()
    {
        $sut = $this->makeInstance('#ff0000');

        $this->assertStringMatchesFormat('#00ffff', (string)$sut->complementary()->toHex(), '');
    }
}
