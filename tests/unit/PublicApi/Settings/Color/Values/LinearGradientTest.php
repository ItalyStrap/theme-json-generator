<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Values;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\LinearGradient;

final class LinearGradientTest extends UnitTestCase
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
        $this->cssColor
            ->__toString()
            ->willReturn('#fff');

        $this->palette
            ->color()
            ->willReturn($this->makeCssColor());

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

    public function testItShouldRejectABlankStringColor(): void
    {
        $sut = $this->makeInstance();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Gradient color must not be empty.');

        $sut->colorStop('  ');
    }
}
