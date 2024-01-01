<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Styles;

trait CommonTests
{
    public function testItShouldBeAnInstanceOfJsonSerializable(): void
    {
        $sut = $this->makeInstance();
        $this->assertInstanceOf(\JsonSerializable::class, $sut);
    }

    public function testItShouldCreateUserDefinedProperty(): void
    {
        $sut = $this->makeInstance();
        $result = $sut->property('style', '#000000')->toArray();

        $this->assertStringMatchesFormat('#000000', $result['style'], '');
    }

    public function testItShouldBeImmutable(): void
    {
        $sut = $this->makeInstance();
        $sut->property('style', '#000000');

        $this->expectException(\RuntimeException::class);
        $sut->property('style', '#000000');
    }

    public function testItShouldBeImmutableAlsoIfICloneIt(): void
    {
        $sut = $this->makeInstance();
        $sut->property('style', '#000000');

        $sut_cloned = clone $sut;

        $this->assertNotEmpty($sut->toArray(), '');
        $this->assertEmpty($sut_cloned->toArray(), '');

        $sut_cloned->property('style', '#000000');
        $sut_cloned->property('new-style', '#000000');


        $this->assertNotSame($sut->toArray(), $sut_cloned->toArray(), '');
    }
}
