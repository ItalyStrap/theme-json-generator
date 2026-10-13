<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Values;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\ColorVariationStep;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\VariationDirection;

final class ColorVariationStepTest extends UnitTestCase
{
    public function testItShouldKeepTheIdentifierAmountAndDirection(): void
    {
        $step = new ColorVariationStep('brand-light', 12.5, VariationDirection::Lighter);

        $this->assertSame('brand-light', $step->identifier);
        $this->assertEqualsWithDelta(12.5, $step->amount, PHP_FLOAT_EPSILON);
        $this->assertSame(VariationDirection::Lighter, $step->direction);
    }

    public function testItShouldAcceptZeroAndOneHundred(): void
    {
        $zero = new ColorVariationStep('brand', 0, VariationDirection::Darker);
        $maximum = new ColorVariationStep('900', 100, VariationDirection::Darker);

        $this->assertEqualsWithDelta(0.0, $zero->amount, PHP_FLOAT_EPSILON);
        $this->assertEqualsWithDelta(100.0, $maximum->amount, PHP_FLOAT_EPSILON);
    }

    /**
     * @dataProvider invalidIdentifierProvider
     */
    public function testItShouldRejectBlankIdentifiers(string $identifier): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Step identifier must not be blank.');

        new ColorVariationStep($identifier, 20, VariationDirection::Lighter);
    }

    public static function invalidIdentifierProvider(): iterable
    {
        yield 'empty' => [''];
        yield 'whitespace' => [" \t\n"];
    }

    /**
     * @dataProvider invalidAmountProvider
     */
    public function testItShouldRejectInvalidAmounts(float $amount): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Step amount must be finite and between 0 and 100.');

        new ColorVariationStep('100', $amount, VariationDirection::Lighter);
    }

    public static function invalidAmountProvider(): iterable
    {
        yield 'negative' => [-0.1];
        yield 'above maximum' => [100.1];
        yield 'positive infinity' => [\INF];
        yield 'negative infinity' => [-\INF];
        yield 'not a number' => [\NAN];
    }
}
