<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Generators\ColorWheel;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel\AnalogousColors;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final class AnalogousColorsTest extends UnitTestCase
{
    protected function makeInstance(string $color): AnalogousColors
    {
        return new AnalogousColors(new CssColor($color));
    }

    public function testItShouldReturnArrayWithColorInfoInterface(): void
    {
        $sut = $this->makeInstance('#ffffff');

        $this->assertContainsOnlyInstancesOf(
            CssColorInterface::class,
            $sut->generate()
        );
    }

    public function testItShouldReturnAnalogousHueAngles(): void
    {
        $sut = $this->makeInstance('hsl(0,50%,50%)');

        $this->assertSame(
            [
                'hsl(0,50%,50%)',
                'hsl(30,50%,50%)',
                'hsl(330,50%,50%)',
            ],
            \array_map(static fn (CssColorInterface $color): string => (string)$color, $sut->generate())
        );
    }

    public function testItShouldAcceptCustomAngle(): void
    {
        $sut = new AnalogousColors(
            new CssColor('hsl(0,50%,50%)'),
            45
        );

        $this->assertSame(
            [
                'hsl(0,50%,50%)',
                'hsl(45,50%,50%)',
                'hsl(315,50%,50%)',
            ],
            \array_map(static fn (CssColorInterface $color): string => (string)$color, $sut->generate())
        );
    }
}
