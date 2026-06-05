<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Styles;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Styles\Spacing;

final class SpacingTest extends UnitTestCase
{
    use CommonTests;

    private function makeInstance(): Spacing
    {
        return new Spacing();
    }

    public function testItShouldCreateCorrectJson(): void
    {
        $sut = $this->makeInstance();
        $result = $sut->blockGap('1rem');

        $this->assertJsonStringEqualsJsonString(
            '{"blockGap":"1rem"}',
            \json_encode($result),
            ''
        );
    }
}
