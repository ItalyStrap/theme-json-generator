<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Generators\ColorWheel;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel\ColorWheelRotation;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final class ColorWheelRotationTest extends UnitTestCase
{
    public function testItShouldReturnTheBaseColorBeforeRotations(): void
    {
        $sut = new ColorWheelRotation(new CssColor('hsl(0,50%,50%)'));

        $this->assertSame(
            [
                'hsl(0,50%,50%)',
                'hsl(90,50%,50%)',
                'hsl(270,50%,50%)',
            ],
            \array_map(static fn (CssColorInterface $color): string => (string)$color, $sut->colorsRotatedBy([90, -90]))
        );
    }

    public function testItShouldIgnoreZeroDegreeRotation(): void
    {
        $sut = new ColorWheelRotation(new CssColor('hsl(0,50%,50%)'));

        $this->assertSame(
            [
                'hsl(0,50%,50%)',
                'hsl(90,50%,50%)',
            ],
            \array_map(static fn (CssColorInterface $color): string => (string)$color, $sut->colorsRotatedBy([0, 90]))
        );
    }
}
