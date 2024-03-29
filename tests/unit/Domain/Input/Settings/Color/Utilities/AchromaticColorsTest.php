<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Settings\Color\Utilities;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\AchromaticColorsExperimental;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\ColorInterface;

class AchromaticColorsTest extends UnitTestCase
{
    protected function makeInstance(): AchromaticColorsExperimental
    {
        return new AchromaticColorsExperimental();
    }

    public function testItShouldReturnArrayWithColorInfoInterface(): void
    {
        $sut = $this->makeInstance();

        $this->assertContainsOnlyInstancesOf(
            ColorInterface::class,
            $sut->generate()
        );
    }

    public function testItShouldReturnArrayWithTwoColors(): void
    {
        $sut = $this->makeInstance();

        $this->assertCount(2, $sut->generate());
        $this->assertSame('#000000', (string)$sut->generate()[0]->toHex());
        $this->assertSame('#ffffff', (string)$sut->generate()[1]->toHex());
    }
}
