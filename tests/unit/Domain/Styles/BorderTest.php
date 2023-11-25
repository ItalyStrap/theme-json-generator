<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Styles;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Styles\Border;

class BorderTest extends UnitTestCase
{
    use CommonTests;

    protected function makeInstance(): Border
    {
        return new Border();
    }

    public function testItShouldCreateCorrectArray()
    {
        $sut = $this->makeInstance();
        $result = $sut
            ->color('#000000')
            ->style('solid')
            ->width('1px')
            ->radius('none')
            ->toArray();

        $this->assertIsArray($result, '');
        $this->assertArrayHasKey('color', $result, '');
        $this->assertArrayHasKey('style', $result, '');
        $this->assertArrayHasKey('width', $result, '');
        $this->assertArrayHasKey('radius', $result, '');

        $this->assertStringMatchesFormat('#000000', $result['color'], '');
        $this->assertStringMatchesFormat('solid', $result['style'], '');
        $this->assertStringMatchesFormat('1px', $result['width'], '');
        $this->assertStringMatchesFormat('none', $result['radius'], '');
    }
}