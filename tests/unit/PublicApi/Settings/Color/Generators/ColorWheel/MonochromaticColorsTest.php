<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Generators\ColorWheel;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel\MonochromaticColorsExperimental;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Transformers\ColorModifier;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final class MonochromaticColorsTest extends UnitTestCase
{
    protected function makeInstance(CssColorInterface $color, array $steps): MonochromaticColorsExperimental
    {
        return new MonochromaticColorsExperimental(new ColorModifier($color), $steps);
    }

    public function testItShouldReturnArrayWithColorInfoInterface(): void
    {
        $sut = $this->makeInstance(
            new CssColor('#ffffff'),
            []
        );

        $this->assertContainsOnlyInstancesOf(
            CssColorInterface::class,
            $sut->generate()
        );
    }

    public function testItShouldReturnArrayWithOneColor(): void
    {
        $sut = $this->makeInstance(
            new CssColor('#ffffff'),
            []
        );

        $this->assertCount(1, $sut->generate());
        $this->assertSame('#ffffff', (string)$sut->generate()[0]);
    }

    public function testItShouldReturnArrayWithTwoColors(): void
    {
        $sut = $this->makeInstance(
            new CssColor('rgb(128, 128, 128)'),
            [20, 40]
        );

        $this->assertCount(5, $sut->generate());
        $this->assertSame('rgb(179,179,179)', (string)$sut->generate()[0]);
        $this->assertSame('rgb(153,153,153)', (string)$sut->generate()[1]);
        $this->assertSame('rgb(128,128,128)', (string)$sut->generate()[2]);
        $this->assertSame('rgb(102,102,102)', (string)$sut->generate()[3]);
        $this->assertSame('rgb(77,77,77)', (string)$sut->generate()[4]);
    }

    public function testItShouldReturnSameColorsWhenGeneratedTwice(): void
    {
        $sut = $this->makeInstance(
            new CssColor('rgb(128, 128, 128)'),
            [20, 40]
        );

        $this->assertSame(
            \array_map(static fn (CssColorInterface $color): string => (string)$color, $sut->generate()),
            \array_map(static fn (CssColorInterface $color): string => (string)$color, $sut->generate())
        );
    }

    public function testItShouldRejectInvalidSteps(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Step must be between 0 and 100.');

        $this->makeInstance(
            new CssColor('#ffffff'),
            [-1]
        );
    }
}
