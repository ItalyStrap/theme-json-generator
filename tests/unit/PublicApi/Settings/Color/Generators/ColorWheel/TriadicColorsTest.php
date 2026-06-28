<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Generators\ColorWheel;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel\TriadicColorsExperimental;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Transformers\ColorModifier;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final class TriadicColorsTest extends UnitTestCase
{
    protected function makeInstance(string $color): TriadicColorsExperimental
    {
        return new TriadicColorsExperimental(new ColorModifier(new CssColor($color)));
    }

    public function testItShouldReturnTriadicHueAngles(): void
    {
        $sut = $this->makeInstance('hsl(0,50%,50%)');

        $this->assertSame(
            [
                'hsl(120,50%,50%)',
                'hsl(0,50%,50%)',
                'hsl(240,50%,50%)',
            ],
            \array_map(static fn (CssColorInterface $color): string => (string)$color, $sut->generate())
        );
    }

    public function testItShouldAcceptCustomAngle(): void
    {
        $sut = new TriadicColorsExperimental(
            new ColorModifier(new CssColor('hsl(0,50%,50%)')),
            90
        );

        $this->assertSame(
            [
                'hsl(90,50%,50%)',
                'hsl(0,50%,50%)',
                'hsl(270,50%,50%)',
            ],
            \array_map(static fn (CssColorInterface $color): string => (string)$color, $sut->generate())
        );
    }
}
