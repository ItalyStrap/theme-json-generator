<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Generators\ColorWheel;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Factories\ColorPresetsFromGeneratorFactory;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorsGenerator;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel\AnalogousColors;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel\ComplementaryColors;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel\SplitComplementaryColors;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel\TetradicColors;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel\TriadicColors;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;
use Spatie\Color\Hsla;

final class ColorWheelIdentifiersTest extends UnitTestCase
{
    /**
     * @dataProvider harmonyProvider
     * @param class-string<ColorsGenerator> $class
     * @param list<string> $identifiers
     * @param list<int> $hues
     * @param list<int> $customHues
     */
    public function testItShouldNamePredefinedPositionsWithoutChangingAngles(
        string $class,
        array $identifiers,
        array $hues,
        array $customHues
    ): void {
        $baseColor = new CssColor('hsl(0,50%,50%)');
        $generator = new $class($baseColor, identifiers: $identifiers);
        $colors = $generator->generate();

        $this->assertSame($identifiers, \array_keys($colors));
        $this->assertSame($hues, \array_values(\array_map(
            static fn (CssColorInterface $color): int => $color->hue(),
            $colors
        )));
        $this->assertSame($baseColor, $colors[$identifiers[0]]);
        $this->assertSame(
            \array_map(static fn (CssColorInterface $color): string => (string) $color, $colors),
            \array_map(static fn (CssColorInterface $color): string => (string) $color, $generator->generate())
        );

        $customColors = (new $class($baseColor, angle: 45, identifiers: $identifiers))->generate();
        $this->assertSame($customHues, \array_values(\array_map(
            static fn (CssColorInterface $color): int => $color->hue(),
            $customColors
        )));
    }

    /**
     * @dataProvider harmonyProvider
     * @param class-string<ColorsGenerator> $class
     * @param list<string> $identifiers
     */
    public function testItShouldKeepExplicitZeroAngleAliases(string $class, array $identifiers): void
    {
        $baseColor = new CssColor(new Hsla(210, 50, 40, 0.123456));
        $colors = (new $class($baseColor, angle: 0, identifiers: $identifiers))->generate();

        $this->assertSame($identifiers, \array_keys($colors));
        $this->assertSame(\array_fill_keys($identifiers, $baseColor), $colors);
        $this->assertSame([$baseColor], (new $class($baseColor, angle: 0))->generate());
    }

    /** @return iterable<string, array{class-string<ColorsGenerator>, list<string>, list<int>, list<int>}> */
    public static function harmonyProvider(): iterable
    {
        yield 'analogous' => [AnalogousColors::class, ['brand', 'highlight', 'accent'], [0, 30, 330], [0, 45, 315]];
        yield 'complementary' => [ComplementaryColors::class, ['brand', 'accent'], [0, 180], [0, 45]];
        yield 'triadic' => [TriadicColors::class, ['brand', 'secondary', 'tertiary'], [0, 120, 240], [0, 45, 315]];
        yield 'split complementary' => [
            SplitComplementaryColors::class,
            ['brand', 'secondary', 'tertiary'],
            [0, 150, 210],
            [0, 45, 315],
        ];
        yield 'tetradic' => [
            TetradicColors::class,
            ['brand', 'secondary', 'tertiary', 'quaternary'],
            [0, 90, 180, 270],
            [0, 45, 90, 135],
        ];
    }

    /**
     * @dataProvider invalidIdentifiersProvider
     * @param class-string<ColorsGenerator> $class
     * @param array<array-key, mixed> $identifiers
     */
    public function testItShouldRejectInvalidIdentifiersAtConstruction(
        string $class,
        array $identifiers,
        string $message
    ): void {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        new $class(new CssColor('#336699'), identifiers: $identifiers);
    }

    /** @return iterable<string, array{class-string<ColorsGenerator>, array<array-key, mixed>, string}> */
    public static function invalidIdentifiersProvider(): iterable
    {
        foreach (self::harmonyProvider() as $name => [$class, $identifiers]) {
            yield $name . ' empty' => [$class, [], 'Identifier count must match the number of color positions.'];
            yield $name . ' too few' => [
                $class,
                \array_slice($identifiers, 1),
                'Identifier count must match the number of color positions.',
            ];
            yield $name . ' too many' => [
                $class,
                [...$identifiers, 'extra'],
                'Identifier count must match the number of color positions.',
            ];
            $duplicate = $identifiers;
            $duplicate[1] = $duplicate[0];
            yield $name . ' duplicate' => [$class, $duplicate, 'Step identifiers must be unique.'];
            $blank = $identifiers;
            $blank[0] = " \t\n";
            yield $name . ' blank' => [$class, $blank, 'Step identifier must not be blank.'];
            $blank[0] = '';
            yield $name . ' empty string' => [$class, $blank, 'Step identifier must not be blank.'];

            foreach ([100, 12.5, true, null, [], new \stdClass()] as $index => $value) {
                $invalidType = $identifiers;
                $invalidType[0] = $value;
                yield $name . ' invalid type ' . $index => [
                    $class,
                    $invalidType,
                    'Identifiers must contain only strings.',
                ];
            }

            $associative = \array_combine(
                \array_map(static fn (int $index): string => 'position-' . $index, \array_keys($identifiers)),
                $identifiers
            );
            yield $name . ' associative' => [$class, $associative, 'Identifiers must be a list.'];
            $sparse = $identifiers;
            unset($sparse[0]);
            $sparse[] = 'brand';
            yield $name . ' sparse' => [$class, $sparse, 'Identifiers must be a list.'];
        }
    }

    public function testItShouldUseNumericIdentifiersAsOutputKeys(): void
    {
        $colors = (new AnalogousColors(new CssColor('#336699'), identifiers: ['500', '100', '900']))->generate();

        $this->assertSame([500, 100, 900], \array_keys($colors));
        $this->assertSame('#336699', (string) $colors[500]);
        $this->assertSame('#333399', (string) $colors[100]);
        $this->assertSame('#339999', (string) $colors[900]);
    }

    public function testItShouldPassCustomIdentifiersToPresetSlugsAndNames(): void
    {
        $generator = new ComplementaryColors(new CssColor('#336699'), identifiers: ['brand', 'accent']);
        $presets = (new ColorPresetsFromGeneratorFactory($generator, 'primary', 'Primary'))->create();

        $this->assertSame(['brand', 'accent'], \array_keys($presets));
        $this->assertSame([
            'slug' => 'primary-brand',
            'name' => 'Primary brand',
            'color' => '#336699',
        ], $presets['brand']->toArray());
        $this->assertSame([
            'slug' => 'primary-accent',
            'name' => 'Primary accent',
            'color' => '#996633',
        ], $presets['accent']->toArray());
    }
}
