<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Styles;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Styles\Color;

class ColorTest extends UnitTestCase
{
    use CommonTests;

    protected function makeInstance(): Color
    {
        $sut = new Color();
        $this->assertInstanceOf(Color::class, $sut, '');
        return $sut;
    }

    public function testItShouldCreateCorrectArray(): void
    {
        $sut = $this->makeInstance();
        $result = $sut
            ->text('#000000')
            ->background('transparent')
            ->gradient('value')
            ->toArray();

        $this->assertIsArray($result, '');
        $this->assertArrayHasKey('text', $result, '');
        $this->assertArrayHasKey('background', $result, '');
        $this->assertArrayHasKey('gradient', $result, '');

        $this->assertStringMatchesFormat('#000000', $result['text'], '');
        $this->assertStringMatchesFormat('transparent', $result['background'], '');
        $this->assertStringMatchesFormat('value', $result['gradient'], '');
    }
}
