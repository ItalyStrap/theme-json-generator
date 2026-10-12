<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Values;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\HueRotationStep;

final class HueRotationStepTest extends UnitTestCase
{
    /**
     * @dataProvider degreesProvider
     */
    public function testItShouldKeepTheIdentifierAndSignedDegrees(int $degrees): void
    {
        $step = new HueRotationStep('brand-accent', $degrees);

        $this->assertSame('brand-accent', $step->identifier);
        $this->assertSame($degrees, $step->degrees);
    }

    public static function degreesProvider(): iterable
    {
        yield 'zero' => [0];
        yield 'positive' => [30];
        yield 'negative' => [-30];
        yield 'multiple turns' => [750];
        yield 'negative multiple turns' => [-750];
        yield 'maximum integer' => [\PHP_INT_MAX];
        yield 'minimum integer' => [\PHP_INT_MIN];
    }

    /**
     * @dataProvider invalidIdentifierProvider
     */
    public function testItShouldRejectBlankIdentifiers(string $identifier): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Step identifier must not be blank.');

        new HueRotationStep($identifier, 30);
    }

    public static function invalidIdentifierProvider(): iterable
    {
        yield 'empty' => [''];
        yield 'whitespace' => [" \t\n"];
    }
}
