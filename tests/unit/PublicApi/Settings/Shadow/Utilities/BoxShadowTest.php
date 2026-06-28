<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Shadow\Utilities;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Shadow\Utilities\BoxShadow;

final class BoxShadowTest extends UnitTestCase
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
        $this->expectExceptionMessage('Box shadow requires both offset-x and offset-y.');
        $var = (string)$sut;
    }

    /**
     * @dataProvider incompleteOffsetsProvider
     */
    public function testItShouldRejectIncompleteOffsets(string $method): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Box shadow requires both offset-x and offset-y.');

        $sut = $this->makeInstance();
        $sut->{$method}('1px');

        (string)$sut;
    }

    public static function incompleteOffsetsProvider(): iterable
    {
        yield 'missing offset-y' => ['offsetX'];
        yield 'missing offset-x' => ['offsetY'];
    }

    public function testItShouldReturnValiShadow(): void
    {
        $color = $this->cssColor
            ->__toString()
            ->willReturn('#fff');

        $this->palette
            ->color()
            ->willReturn($this->makeCssColor());

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

    public function testItShouldSerializeRepeatedlyWithoutChangingState(): void
    {
        $sut = $this->makeInstance();
        $sut->offsetX('0')
            ->offsetY('10px')
            ->blur('0')
            ->spread('0')
            ->color('#fff');

        $this->assertSame('0 10px 0 0 #ffffff', (string)$sut);
        $this->assertSame('0 10px 0 0 #ffffff', (string)$sut);
    }

    public function testWithColorObject(): void
    {
        $color = $this->cssColor
            ->__toString()
            ->willReturn('#fff');

        $sut = $this->makeInstance();
        $sut->offsetX('0');
        $sut->offsetY('10px');
        $sut->color($this->makeCssColor());
        $this->assertSame(
            '0 10px #fff',
            (string)$sut
        );
    }
}
