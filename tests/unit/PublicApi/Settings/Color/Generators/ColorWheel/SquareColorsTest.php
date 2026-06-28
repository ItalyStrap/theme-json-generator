<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Generators\ColorWheel;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel\SquareColorsExperimental;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Transformers\ColorModifier;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final class SquareColorsTest extends UnitTestCase
{
    protected function makeInstance(string $color): SquareColorsExperimental
    {
        return new SquareColorsExperimental(new ColorModifier(new CssColor($color)));
    }

    public function testItShouldReturnArrayWithColorInfoInterface(): void
    {
        $sut = $this->makeInstance('#ffffff');

        $this->assertContainsOnlyInstancesOf(
            CssColorInterface::class,
            $sut->generate()
        );
    }

    public function testItShouldReturnFourColorsWithSquareHueAngles(): void
    {
        $sut = $this->makeInstance('hsl(0,50%,50%)');

        $this->assertSame(
            [
                'hsl(0,50%,50%)',
                'hsl(90,50%,50%)',
                'hsl(180,50%,50%)',
                'hsl(270,50%,50%)',
            ],
            \array_map(static fn (CssColorInterface $color): string => (string)$color, $sut->generate())
        );
    }

    public function testItShouldAcceptCustomAngle(): void
    {
        $sut = new SquareColorsExperimental(
            new ColorModifier(new CssColor('hsl(0,50%,50%)')),
            60
        );

        $this->assertSame(
            [
                'hsl(0,50%,50%)',
                'hsl(60,50%,50%)',
                'hsl(120,50%,50%)',
                'hsl(180,50%,50%)',
            ],
            \array_map(static fn (CssColorInterface $color): string => (string)$color, $sut->generate())
        );
    }
}
