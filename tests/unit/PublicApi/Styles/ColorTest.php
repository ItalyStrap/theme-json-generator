<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Styles;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Styles\Color;

final class ColorTest extends UnitTestCase
{
    use CommonTests;

    protected function makeInstance(): Color
    {
        return $this->makeStyles()->color();
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
