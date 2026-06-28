<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Generators\ColorWheel;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel\AnalogousColorsExperimental;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Transformers\ColorModifier;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final class AnalogousColorsTest extends UnitTestCase
{
    protected function makeInstance(string $color): AnalogousColorsExperimental
    {
        return new AnalogousColorsExperimental(new ColorModifier(new CssColor($color)));
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
                'hsl(30,50%,50%)',
                'hsl(0,50%,50%)',
                'hsl(330,50%,50%)',
            ],
            \array_map(static fn (CssColorInterface $color): string => (string)$color, $sut->generate())
        );
    }

    public function testItShouldAcceptCustomAngle(): void
    {
        $sut = new AnalogousColorsExperimental(
            new ColorModifier(new CssColor('hsl(0,50%,50%)')),
            45
        );

        $this->assertSame(
            [
                'hsl(45,50%,50%)',
                'hsl(0,50%,50%)',
                'hsl(315,50%,50%)',
            ],
            \array_map(static fn (CssColorInterface $color): string => (string)$color, $sut->generate())
        );
    }
}
