<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Factories;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Factories\ColorFactory;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;

final class ColorFactoryTest extends UnitTestCase
{
    protected function makeInstance(): ColorFactory
    {
        return new ColorFactory();
    }

    public function testItShouldBeInstantiable(): void
    {
        $sut = $this->makeInstance();
        $this->assertInstanceOf(ColorFactory::class, $sut, 'Should be an instance of ColorFactory');
    }

    public function testItShouldReturnColorInstanceFromColorInfo(): void
    {
        $sut = $this->makeInstance();
        $color = $sut->fromColorInfo(new CssColor('#ffffff'));
        $this->assertInstanceOf(CssColor::class, $color, 'Should be an instance of Color');
        $this->assertSame('#ffffff', (string) $color, 'Should be equals');
    }

    public function testItShouldReturnColorInstanceFromColorString(): void
    {
        $sut = $this->makeInstance();
        $color = $sut->fromColorString('rgba(255,255,255,1.00)');
        $this->assertInstanceOf(CssColor::class, $color, 'Should be an instance of Color');
        $this->assertSame('rgba(255,255,255,1.00)', (string) $color, 'Should be equals');
    }

    public function testItShouldReturnColorInstanceFromHsla(): void
    {
        $sut = $this->makeInstance();
        $color = $sut->hsla(0, 0, 100);
        $this->assertInstanceOf(CssColor::class, $color, 'Should be an instance of Color');
        $this->assertSame('hsla(0,0%,100%,1)', (string) $color, 'Should be equals');
    }

    public function testItShouldReturnColorInstanceFromRgba(): void
    {
        $sut = $this->makeInstance();
        $color = $sut->rgba(255, 255, 255);
        $this->assertInstanceOf(CssColor::class, $color, 'Should be an instance of Color');
        $this->assertSame('rgba(255,255,255,1.00)', (string) $color, 'Should be equals');
    }
}
