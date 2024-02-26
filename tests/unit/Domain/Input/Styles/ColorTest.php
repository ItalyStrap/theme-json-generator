<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Styles;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Color;

class ColorTest extends UnitTestCase
{
    use CommonTests;

    protected function makeInstance(): Color
    {
        return new Color();
    }

    public function testItShouldCreateCorrectArray(): void
    {
        $sut = $this->makeInstance();
        $result = $sut
            ->text('#000000')
            ->background('transparent')
            ->gradient('value')
            ->link('#000000')
            ->toArray();

        $this->assertIsArray($result, '');
        $this->assertArrayHasKey('text', $result, '');
        $this->assertArrayHasKey('background', $result, '');
        $this->assertArrayHasKey('gradient', $result, '');
        $this->assertArrayHasKey('link', $result, '');

        $this->assertStringMatchesFormat('#000000', $result['text'], '');
        $this->assertStringMatchesFormat('transparent', $result['background'], '');
        $this->assertStringMatchesFormat('value', $result['gradient'], '');
        $this->assertStringMatchesFormat('#000000', $result['link'], '');
    }

    public function testItShouldCreateCorrectJson(): void
    {
        $sut = $this->makeInstance();
        $result = $sut
            ->text('#000000')
            ->background('transparent')
            ->gradient('value');

        $this->assertJsonStringEqualsJsonString(
            '{"text":"#000000","background":"transparent","gradient":"value"}',
            \json_encode($result),
            ''
        );
    }
}
