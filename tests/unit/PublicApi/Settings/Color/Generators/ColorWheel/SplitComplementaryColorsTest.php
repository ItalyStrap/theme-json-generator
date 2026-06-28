<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Generators\ColorWheel;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel\SplitComplementaryColorsExperimental;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Transformers\ColorModifier;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final class SplitComplementaryColorsTest extends UnitTestCase
{
    protected function makeInstance(string $color): SplitComplementaryColorsExperimental
    {
        return new SplitComplementaryColorsExperimental(new ColorModifier(new CssColor($color)));
    }

    public function testItShouldReturnSplitComplementaryHueAngles(): void
    {
        $sut = $this->makeInstance('hsl(0,50%,50%)');

        $this->assertSame(
            [
                'hsl(150,50%,50%)',
                'hsl(0,50%,50%)',
                'hsl(210,50%,50%)',
            ],
            \array_map(static fn (CssColorInterface $color): string => (string)$color, $sut->generate())
        );
    }

    public function testItShouldAcceptCustomAngle(): void
    {
        $sut = new SplitComplementaryColorsExperimental(
            new ColorModifier(new CssColor('hsl(0,50%,50%)')),
            135
        );

        $this->assertSame(
            [
                'hsl(135,50%,50%)',
                'hsl(0,50%,50%)',
                'hsl(225,50%,50%)',
            ],
            \array_map(static fn (CssColorInterface $color): string => (string)$color, $sut->generate())
        );
    }
}
