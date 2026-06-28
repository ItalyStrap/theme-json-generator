<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Generators\ColorWheel;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel\ComplementaryColorsExperimental;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Transformers\ColorModifier;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final class ComplementaryColorsTest extends UnitTestCase
{
    protected function makeInstance(string $color): ComplementaryColorsExperimental
    {
        return new ComplementaryColorsExperimental(new ColorModifier(new CssColor($color)));
    }

    public function testItShouldReturnComplementaryHueAngles(): void
    {
        $sut = $this->makeInstance('hsl(0,50%,50%)');

        $this->assertSame(
            [
                'hsl(0,50%,50%)',
                'hsl(180,50%,50%)',
            ],
            \array_map(static fn (CssColorInterface $color): string => (string)$color, $sut->generate())
        );
    }

    public function testItShouldAcceptCustomAngle(): void
    {
        $sut = new ComplementaryColorsExperimental(
            new ColorModifier(new CssColor('hsl(0,50%,50%)')),
            150
        );

        $this->assertSame(
            [
                'hsl(0,50%,50%)',
                'hsl(150,50%,50%)',
            ],
            \array_map(static fn (CssColorInterface $color): string => (string)$color, $sut->generate())
        );
    }
}
