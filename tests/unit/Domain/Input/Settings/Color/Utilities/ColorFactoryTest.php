<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Settings\Color\Utilities;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\Color;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\ColorFactory;

class ColorFactoryTest extends UnitTestCase
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
        $color = $sut->fromColorInfo(new Color('#ffffff'));
        $this->assertInstanceOf(Color::class, $color, 'Should be an instance of Color');
        $this->assertSame('#ffffff', (string) $color, 'Should be equals');
    }

    public function testItShouldReturnColorInstanceFromColorString(): void
    {
        $sut = $this->makeInstance();
        $color = $sut->fromColorString('rgba(255,255,255,1.00)');
        $this->assertInstanceOf(Color::class, $color, 'Should be an instance of Color');
        $this->assertSame('rgba(255,255,255,1.00)', (string) $color, 'Should be equals');
    }

    public function testItShouldReturnColorInstanceFromHsla(): void
    {
        $sut = $this->makeInstance();
        $color = $sut->hsla(0, 0, 100);
        $this->assertInstanceOf(Color::class, $color, 'Should be an instance of Color');
        $this->assertSame('hsla(0,0%,100%,1)', (string) $color, 'Should be equals');
    }

    public function testItShouldReturnColorInstanceFromRgba(): void
    {
        $sut = $this->makeInstance();
        $color = $sut->rgba(255, 255, 255);
        $this->assertInstanceOf(Color::class, $color, 'Should be an instance of Color');
        $this->assertSame('rgba(255,255,255,1.00)', (string) $color, 'Should be equals');
    }
}
