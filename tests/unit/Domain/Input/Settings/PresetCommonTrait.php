<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Settings;

trait PresetCommonTrait
{
    public function testItShouldReturnTheSlug(): void
    {
        $sut = $this->makeInstance();
        $this->assertSame($this->slug, $sut->slug());
    }

    public function testItShouldReturnRef(): void
    {
        $sut = $this->makeInstance();
        $this->assertSame('{{' . $sut->category() . '.' . $sut->slug() . '}}', $sut->ref());
    }

    public function testItShouldReturnProp(): void
    {
        $sut = $this->makeInstance();
        $this->assertStringContainsString($sut->slug(), $sut->prop(), 'The prop should contain the slug');
    }

    public function testItShouldReturnVar(): void
    {
        $sut = $this->makeInstance();
        $this->assertStringContainsString($sut->prop(), $sut->var(), 'The var should contain the prop');
    }

    public function testItShouldBeCorrectlyConvertedToString(): void
    {
        $sut = $this->makeInstance();
        $this->assertNotEmpty((string)$sut);
    }
}
