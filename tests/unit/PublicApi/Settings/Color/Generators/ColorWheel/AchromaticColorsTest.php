<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Generators\ColorWheel;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel\AchromaticColorsExperimental;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final class AchromaticColorsTest extends UnitTestCase
{
    protected function makeInstance(): AchromaticColorsExperimental
    {
        return new AchromaticColorsExperimental();
    }

    public function testItShouldReturnArrayWithColorInfoInterface(): void
    {
        $sut = $this->makeInstance();

        $this->assertContainsOnlyInstancesOf(
            CssColorInterface::class,
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
