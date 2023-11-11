<?php

namespace Domain\Settings\Color\Utilities;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities\AnalogousColorsExperimental;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities\ColorInfo;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities\ColorInfoInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities\ColorModifier;

class AnalogousColorsTest extends UnitTestCase
{
    protected function makeInstance(string $color): AnalogousColorsExperimental
    {
        return new AnalogousColorsExperimental(new ColorModifier(new ColorInfo($color)));
    }

    public function testItShouldReturnArrayWithColorInfoInterface(): void
    {
        $sut = $this->makeInstance('#ffffff');

        $this->assertContainsOnlyInstancesOf(
            ColorInfoInterface::class,
            $sut->generate()
        );
    }
}
