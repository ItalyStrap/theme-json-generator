<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Settings\Color\Utilities;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\BoxShadow;

class BoxShadowTest extends UnitTestCase
{
    private function makeInstance(): BoxShadow
    {
        return new BoxShadow();
    }

    public function testItShouldBeInstantiable(): void
    {
        $sut = $this->makeInstance();
        $this->assertInstanceOf(BoxShadow::class, $sut);
    }

    public function testItShouldThrowExceptionWhenNoOffsetAreProvided(): void
    {
        $sut = $this->makeInstance();
        $this->expectException(\RuntimeException::class);
        $var = (string)$sut;
    }

    public function testItShouldReturnValiShadow(): void
    {
        $color = $this->colorInfo
            ->__toString()
            ->willReturn('#fff');

        $this->palette
            ->color()
            ->willReturn($this->makeColorInfo());

        $this->palette
            ->var('#fff')
            ->willReturn('var(--color-foo, #fff)');

        $sut = $this->makeInstance();
        $sut->offsetX('0');
        $sut->offsetY('10px');
        $sut->color($this->makePalette());
        $this->assertSame(
            '0 10px var(--color-foo, #fff)',
            (string)$sut
        );
    }

    public function testWithStringColor(): void
    {
        $sut = $this->makeInstance();
        $sut->offsetX('0')
            ->offsetY('10px')
            ->blur('0')
            ->spread('0')
            ->color('#fff');
        $this->assertSame(
            '0 10px 0 0 #ffffff',
            (string)$sut
        );
    }

    public function testWithColorObject(): void
    {
        $color = $this->colorInfo
            ->__toString()
            ->willReturn('#fff');

        $sut = $this->makeInstance();
        $sut->offsetX('0');
        $sut->offsetY('10px');
        $sut->color($this->makeColorInfo());
        $this->assertSame(
            '0 10px #fff',
            (string)$sut
        );
    }
}
