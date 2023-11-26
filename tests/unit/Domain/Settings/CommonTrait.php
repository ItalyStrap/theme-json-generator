<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Settings;

trait CommonTrait
{
    public function testItShouldReturnTheSlug(): void
    {
        $sut = $this->makeInstance();
        $this->assertSame($this->slug, $sut->slug());
    }
}
