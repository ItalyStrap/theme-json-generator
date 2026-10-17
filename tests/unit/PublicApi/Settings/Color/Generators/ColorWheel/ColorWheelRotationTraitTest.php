<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Generators\ColorWheel;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorsGenerator;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel\ColorWheelRotationTrait;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;
use Spatie\Color\Hsla;

final class ColorWheelRotationTraitTest extends UnitTestCase
{
    public function testItShouldReturnTheBaseColorBeforeRotations(): void
    {
        $sut = $this->generatorFrom(new CssColor('hsl(0,50%,50%)'), [90, -90]);

        $this->assertSame(
            [
                'hsl(0,50%,50%)',
                'hsl(90,50%,50%)',
                'hsl(270,50%,50%)',
            ],
            \array_map(static fn (CssColorInterface $color): string => (string)$color, $sut->generate())
        );
    }

    public function testItShouldIgnoreZeroDegreeRotation(): void
    {
        $sut = $this->generatorFrom(new CssColor('hsl(0,50%,50%)'), [0, 90]);

        $this->assertSame(
            [
                'hsl(0,50%,50%)',
                'hsl(90,50%,50%)',
            ],
            \array_map(static fn (CssColorInterface $color): string => (string)$color, $sut->generate())
        );
    }

    public function testItShouldReturnOnlyTheOriginalInstanceWithoutRotations(): void
    {
        $baseColor = new CssColor('#336699');

        $this->assertSame([$baseColor], $this->generatorFrom($baseColor, [])->generate());
        $this->assertSame([$baseColor], $this->generatorFrom($baseColor, [0, 0])->generate());
    }

    public function testItShouldPreserveSequentialKeysOrderAndRepeatedRotations(): void
    {
        $baseColor = new CssColor('hsl(0,50%,50%)');
        $colors = $this->generatorFrom($baseColor, [
            'skip' => 0,
            'left' => -90,
            'right' => 450,
            'alias' => 90,
        ])->generate();

        $this->assertSame([0, 1, 2, 3], \array_keys($colors));
        $this->assertSame($baseColor, $colors[0]);
        $this->assertSame([
            'hsl(0,50%,50%)',
            'hsl(270,50%,50%)',
            'hsl(90,50%,50%)',
            'hsl(90,50%,50%)',
        ], \array_map(static fn (CssColorInterface $color): string => (string) $color, $colors));
    }

    public function testItShouldKeepFullTurnAliasesWithoutConvertingTheOriginalColor(): void
    {
        $baseColor = new CssColor(new Hsla(210, 50, 40, 0.123456));
        $colors = $this->generatorFrom($baseColor, [0, 360, -720])->generate();

        $this->assertSame([$baseColor, $baseColor, $baseColor], $colors);
    }

    public function testItShouldUseSafeRotationAtIntegerLimits(): void
    {
        $colors = $this->generatorFrom(new CssColor('hsl(350,50%,40%)'), [
            \PHP_INT_MAX,
            \PHP_INT_MIN,
        ])->generate();

        $this->assertCount(3, $colors);
        $maximumHue = ((350 + (\PHP_INT_MAX % 360)) % 360 + 360) % 360;
        $minimumHue = ((350 + (\PHP_INT_MIN % 360)) % 360 + 360) % 360;
        $this->assertSame(\sprintf('hsl(%d,50%%,40%%)', $maximumHue), (string) $colors[1]);
        $this->assertSame(\sprintf('hsl(%d,50%%,40%%)', $minimumHue), (string) $colors[2]);
    }

    public function testItShouldExposeRotationOnlyAsAPrivateTraitMethod(): void
    {
        $trait = new \ReflectionClass(ColorWheelRotationTrait::class);

        $this->assertTrue($trait->isTrait());
        $this->assertTrue($trait->getMethod('colorsRotatedBy')->isPrivate());
    }

    /**
     * @dataProvider invalidIdentifiersProvider
     * @param array<array-key, mixed> $identifiers
     */
    public function testItShouldValidateIdentifiersInAnAnonymousGenerator(array $identifiers, string $message): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        $this->generatorFrom(new CssColor('#336699'), [30, -30], $identifiers)->generate();
    }

    /** @return iterable<string, array{array<array-key, mixed>, string}> */
    public static function invalidIdentifiersProvider(): iterable
    {
        yield 'wrong count' => [['brand'], 'Identifier count must match the number of color positions.'];
        yield 'wrong type' => [['brand', 100, 'accent'], 'Identifiers must contain only strings.'];
        yield 'associative' => [
            ['base' => 'brand', 'positive' => 'highlight', 'negative' => 'accent'],
            'Identifiers must be a list.',
        ];
    }

    public function testItShouldPreserveCustomIdentifiersInAnAnonymousGenerator(): void
    {
        $baseColor = new CssColor('hsl(0,50%,50%)');
        $colors = $this->generatorFrom($baseColor, [90, -90], ['brand', 'right', 'left'])->generate();

        $this->assertSame(['brand', 'right', 'left'], \array_keys($colors));
        $this->assertSame($baseColor, $colors['brand']);
        $this->assertSame('hsl(90,50%,50%)', (string) $colors['right']);
        $this->assertSame('hsl(270,50%,50%)', (string) $colors['left']);
    }

    /**
     * @param int[] $degrees
     * @param array<array-key, mixed>|null $identifiers
     */
    private function generatorFrom(
        CssColorInterface $color,
        array $degrees,
        ?array $identifiers = null
    ): ColorsGenerator {
        return new class ($color, $degrees, $identifiers) implements ColorsGenerator {
            use ColorWheelRotationTrait;

            /**
             * @param int[] $degrees
             * @param array<array-key, mixed>|null $identifiers
             */
            public function __construct(
                private CssColorInterface $color,
                private array $degrees,
                private ?array $identifiers
            ) {
            }

            public function generate(): array
            {
                return $this->colorsRotatedBy($this->color, $this->degrees, $this->identifiers);
            }
        };
    }
}
