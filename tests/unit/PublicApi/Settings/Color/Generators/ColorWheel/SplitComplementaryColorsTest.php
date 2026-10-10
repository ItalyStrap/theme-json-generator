<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Generators\ColorWheel;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel\SplitComplementaryColors;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final class SplitComplementaryColorsTest extends UnitTestCase
{
    protected function makeInstance(string $color): SplitComplementaryColors
    {
        return new SplitComplementaryColors(new CssColor($color));
    }

    public function testItShouldReturnSplitComplementaryHueAngles(): void
    {
        $sut = $this->makeInstance('hsl(0,50%,50%)');

        $this->assertSame(
            [
                'hsl(0,50%,50%)',
                'hsl(150,50%,50%)',
                'hsl(210,50%,50%)',
            ],
            \array_map(static fn (CssColorInterface $color): string => (string)$color, $sut->generate())
        );
    }

    public function testItShouldAcceptCustomAngle(): void
    {
        $sut = new SplitComplementaryColors(
            new CssColor('hsl(0,50%,50%)'),
            135
        );

        $this->assertSame(
            [
                'hsl(0,50%,50%)',
                'hsl(135,50%,50%)',
                'hsl(225,50%,50%)',
            ],
            \array_map(static fn (CssColorInterface $color): string => (string)$color, $sut->generate())
        );
    }
}
