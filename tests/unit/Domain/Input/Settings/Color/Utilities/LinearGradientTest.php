<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Settings\Color\Utilities;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\LinearGradient;

class LinearGradientTest extends UnitTestCase
{
    private function makeInstance(): LinearGradient
    {
        return new LinearGradient();
    }

    public function testItShouldBeInstantiable(): void
    {
        $sut = $this->makeInstance();
        $this->assertInstanceOf(LinearGradient::class, $sut);
    }

    public function testItShouldThrowExceptionWhenToStringWithLessThanTwoColors(): void
    {
        $sut = $this->makeInstance();
        $this->expectException(\RuntimeException::class);
        $var = (string)$sut;
    }

    public function testItShouldReturnEmptyStringWhenToStringWithDirection(): void
    {
        $sut = $this->makeInstance();
        $sut->direction('to bottom');
        $this->expectException(\RuntimeException::class);
        $var = (string)$sut;
    }

    public function testItShouldReturnEmptyStringWhenToStringWithDirectionAndColorAndStop(): void
    {
        $this->colorInfo
            ->__toString()
            ->willReturn('#fff');

        $this->palette
            ->color()
            ->willReturn($this->makeColorInfo());

        $this->palette
            ->var('#fff')
            ->willReturn('var(--color-foo, #fff)');

        $sut = $this->makeInstance();
        $sut->direction('to bottom');
        $sut->colorStop($this->makePalette());
        $sut->colorStop($this->makePalette());
        $this->assertSame(
            'linear-gradient(to bottom, var(--color-foo, #fff), var(--color-foo, #fff))',
            (string)$sut
        );
    }
}
