<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Utilities;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\Color;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\ColorInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\ShadesGeneratorExperimental;

final class ShadesGeneratorExperimentalTest extends UnitTestCase
{
    public function testItShouldGenerateFractionalPercentageSteps(): void
    {
        $colors = (new ShadesGeneratorExperimental(
            new Color('#000000'),
            'black',
            100,
            200,
            25
        ))->toColors();

        $this->assertSame([100, 125, 150, 175, 200], \array_keys($colors));
        $this->assertContainsOnlyInstancesOf(ColorInterface::class, $colors);
    }

    public function testItShouldIncludeTheMaximumShade(): void
    {
        $colors = (new ShadesGeneratorExperimental(
            new Color('#000000'),
            'black',
            100,
            300,
            100
        ))->toColors();

        $this->assertSame([100, 200, 300], \array_keys($colors));
    }

    /**
     * @dataProvider invalidIncrementProvider
     */
    public function testItShouldRejectNonPositiveIncrements(int $increment): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Shade increment must be greater than zero.');

        new ShadesGeneratorExperimental(
            new Color('#000000'),
            'black',
            100,
            300,
            $increment
        );
    }

    public static function invalidIncrementProvider(): iterable
    {
        yield 'zero' => [0];
        yield 'negative' => [-25];
    }

    public function testItShouldRejectNegativeMinimum(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Minimum shade must be zero or greater.');

        new ShadesGeneratorExperimental(new Color('#000000'), 'black', -1, 300, 25);
    }

    public function testItShouldRejectMaximumBelowMinimum(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Maximum shade must be greater than or equal to minimum shade.');

        new ShadesGeneratorExperimental(new Color('#000000'), 'black', 300, 100, 25);
    }
}
