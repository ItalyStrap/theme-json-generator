<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Generators;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ShadesExperimental;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;

final class ShadesExperimentalTest extends UnitTestCase
{
    public function testItShouldGenerateFractionalPercentageSteps(): void
    {
        $colors = (new ShadesExperimental(
            new CssColor('#000000'),
            'black',
            100,
            200,
            25
        ))->toCssColors();

        $this->assertSame([100, 125, 150, 175, 200], \array_keys($colors));
        $this->assertContainsOnlyInstancesOf(CssColorInterface::class, $colors);
    }

    public function testItShouldIncludeTheMaximumShade(): void
    {
        $colors = (new ShadesExperimental(
            new CssColor('#000000'),
            'black',
            100,
            300,
            100
        ))->toCssColors();

        $this->assertSame([100, 200, 300], \array_keys($colors));
    }

    /**
     * @dataProvider invalidIncrementProvider
     */
    public function testItShouldRejectNonPositiveIncrements(int $increment): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Shade increment must be greater than zero.');

        new ShadesExperimental(
            new CssColor('#000000'),
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

        new ShadesExperimental(new CssColor('#000000'), 'black', -1, 300, 25);
    }

    public function testItShouldRejectMaximumBelowMinimum(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Maximum shade must be greater than or equal to minimum shade.');

        new ShadesExperimental(new CssColor('#000000'), 'black', 300, 100, 25);
    }
}
