<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Settings\Color\Utilities;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities\ColorInfo;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities\ColorInfoInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities\ColorModifier;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities\MonochromaticColorsExperimental;

class MonochromaticColorsTest extends UnitTestCase
{
    protected function makeInstance(ColorInfoInterface $color, array $steps): MonochromaticColorsExperimental
    {
        return new MonochromaticColorsExperimental(new ColorModifier($color), $steps);
    }

    public function testItShouldReturnArrayWithColorInfoInterface(): void
    {
        $sut = $this->makeInstance(
            new ColorInfo('#ffffff'),
            []
        );

        $this->assertContainsOnlyInstancesOf(
            ColorInfoInterface::class,
            $sut->generate()
        );
    }

    public function testItShouldReturnArrayWithOneColor(): void
    {
        $sut = $this->makeInstance(
            new ColorInfo('#ffffff'),
            []
        );

        $this->assertCount(1, $sut->generate());
        $this->assertSame('#ffffff', (string)$sut->generate()[0]);
    }

    public function testItShouldReturnArrayWithTwoColors(): void
    {
        $sut = $this->makeInstance(
            new ColorInfo('rgb(128, 128, 128)'),
            [20, 40]
        );

        $this->assertCount(5, $sut->generate());
        $this->assertSame('rgb(179,179,179)', (string)$sut->generate()[0]);
        $this->assertSame('rgb(153,153,153)', (string)$sut->generate()[1]);
        $this->assertSame('rgb(128,128,128)', (string)$sut->generate()[2]);
        $this->assertSame('rgb(102,102,102)', (string)$sut->generate()[3]);
        $this->assertSame('rgb(77,77,77)', (string)$sut->generate()[4]);
    }

    public function testItShouldReturnArrayWithTwoColorss(): void
    {
        $sut = $this->makeInstance(
            new ColorInfo('rgb(128, 128, 128)'),
            [0.2, 0.4]
        );

        $this->assertCount(5, $sut->generate());
        $this->assertSame('rgb(179,179,179)', (string)$sut->generate()[0]);
        $this->assertSame('rgb(153,153,153)', (string)$sut->generate()[1]);
        $this->assertSame('rgb(128,128,128)', (string)$sut->generate()[2]);
        $this->assertSame('rgb(102,102,102)', (string)$sut->generate()[3]);
        $this->assertSame('rgb(77,77,77)', (string)$sut->generate()[4]);
    }
}
