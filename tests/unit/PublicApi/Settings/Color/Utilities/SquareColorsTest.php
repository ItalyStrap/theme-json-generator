<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Utilities;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\Color;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\ColorInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\ColorModifier;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\SquareColorsExperimental;

final class SquareColorsTest extends UnitTestCase
{
    protected function makeInstance(string $color): SquareColorsExperimental
    {
        return new SquareColorsExperimental(new ColorModifier(new Color($color)));
    }

    public function testItShouldReturnArrayWithColorInfoInterface(): void
    {
        $sut = $this->makeInstance('#ffffff');

        $this->assertContainsOnlyInstancesOf(
            ColorInterface::class,
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
            \array_map(static fn (ColorInterface $color): string => (string)$color, $sut->generate())
        );
    }
}
