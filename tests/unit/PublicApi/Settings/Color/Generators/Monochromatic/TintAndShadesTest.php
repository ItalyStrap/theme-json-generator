<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Generators\Monochromatic;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Container\PresetsToThemeJson;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Factories\ColorPresetsFromGeneratorFactory;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorsGenerator;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\Monochromatic\TintAndShades;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\ColorVariationStep;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\VariationDirection;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;
use Spatie\Color\Hsla;

final class TintAndShadesTest extends UnitTestCase
{
    public function testItShouldReturnNoColorsWithoutSteps(): void
    {
        $generator = new TintAndShades(new CssColor('#336699'), []);

        $this->assertInstanceOf(ColorsGenerator::class, $generator);
        $this->assertSame([], $generator->generate());
    }

    public function testItShouldGenerateOneColorPerStepUsingRgbMixing(): void
    {
        $baseColor = new CssColor('#336699');
        $colors = (new TintAndShades($baseColor, [
            new ColorVariationStep('100', 20, VariationDirection::Lighter),
            new ColorVariationStep('900', 20, VariationDirection::Darker),
        ]))->generate();

        $this->assertSame([100, 900], \array_keys($colors));
        $this->assertCount(2, $colors);
        $this->assertContainsOnlyInstancesOf(CssColorInterface::class, $colors);
        $this->assertSame('#5c85ad', (string) $colors[100]);
        $this->assertSame('#29527a', (string) $colors[900]);
        $this->assertSame('#336699', (string) $baseColor);
    }

    public function testItShouldNotAddAnImplicitBaseColorForOneStep(): void
    {
        $colors = (new TintAndShades(new CssColor('#336699'), [
            new ColorVariationStep('shadow', 20, VariationDirection::Darker),
        ]))->generate();

        $this->assertSame(['shadow'], \array_keys($colors));
        $this->assertSame('#29527a', (string) $colors['shadow']);
    }

    public function testItShouldPreserveConfiguredOrderAndUnequalDirectionCounts(): void
    {
        $colors = (new TintAndShades(new CssColor('#336699'), [
            new ColorVariationStep('shadow', 20, VariationDirection::Darker),
            new ColorVariationStep('highlight', 40, VariationDirection::Lighter),
            new ColorVariationStep('subtle', 10, VariationDirection::Lighter),
        ]))->generate();

        $this->assertSame(['shadow', 'highlight', 'subtle'], \array_keys($colors));
        $this->assertSame('#29527a', (string) $colors['shadow']);
        $this->assertSame('#85a3c2', (string) $colors['highlight']);
        $this->assertSame('#4775a3', (string) $colors['subtle']);
    }

    public function testItShouldReturnTheOriginalInstanceForZeroInEitherDirection(): void
    {
        $baseColor = new CssColor(new Hsla(210, 50, 40, 0.123456));
        $colors = (new TintAndShades($baseColor, [
            new ColorVariationStep('500', 0, VariationDirection::Lighter),
            new ColorVariationStep('brand', 0, VariationDirection::Darker),
        ]))->generate();

        $this->assertSame([500, 'brand'], \array_keys($colors));
        $this->assertSame($baseColor, $colors[500]);
        $this->assertSame($baseColor, $colors['brand']);
    }

    public function testItShouldApplyEveryStepToTheOriginalColor(): void
    {
        $colors = (new TintAndShades(new CssColor('#336699'), [
            new ColorVariationStep('first', 20, VariationDirection::Lighter),
            new ColorVariationStep('second', 20, VariationDirection::Lighter),
        ]))->generate();

        $this->assertSame('#5c85ad', (string) $colors['first']);
        $this->assertSame('#5c85ad', (string) $colors['second']);
    }

    public function testItShouldReturnWhiteAndBlackAtOneHundredPercent(): void
    {
        $colors = (new TintAndShades(new CssColor('#336699'), [
            new ColorVariationStep('white', 100, VariationDirection::Lighter),
            new ColorVariationStep('black', 100, VariationDirection::Darker),
        ]))->generate();

        $this->assertSame('#ffffff', (string) $colors['white']);
        $this->assertSame('#000000', (string) $colors['black']);
    }

    public function testItShouldKeepEqualColorsWithDifferentIdentifiers(): void
    {
        $colors = (new TintAndShades(new CssColor('#000000'), [
            new ColorVariationStep('dark', 20, VariationDirection::Darker),
            new ColorVariationStep('darker', 40, VariationDirection::Darker),
        ]))->generate();

        $this->assertSame(['dark', 'darker'], \array_keys($colors));
        $this->assertSame('#000000', (string) $colors['dark']);
        $this->assertSame('#000000', (string) $colors['darker']);
    }

    public function testItShouldAcceptFractionalMixingPercentages(): void
    {
        $colors = (new TintAndShades(new CssColor('#808080'), [
            new ColorVariationStep('light', 12.5, VariationDirection::Lighter),
            new ColorVariationStep('dark', 12.5, VariationDirection::Darker),
        ]))->generate();

        $this->assertSame('#909090', (string) $colors['light']);
        $this->assertSame('#707070', (string) $colors['dark']);
    }

    public function testItShouldPreserveTheSourceFormatAndTransparency(): void
    {
        $colors = (new TintAndShades(new CssColor('rgba(128,128,128,0.5)'), [
            new ColorVariationStep('light', 20, VariationDirection::Lighter),
            new ColorVariationStep('dark', 20, VariationDirection::Darker),
        ]))->generate();

        $this->assertSame('rgba(153,153,153,0.50)', (string) $colors['light']);
        $this->assertSame('rgba(102,102,102,0.50)', (string) $colors['dark']);
        $this->assertEqualsWithDelta(0.5, $colors['light']->alpha(), PHP_FLOAT_EPSILON);
        $this->assertEqualsWithDelta(0.5, $colors['dark']->alpha(), PHP_FLOAT_EPSILON);
    }

    public function testItShouldRejectDuplicateIdentifiersBeforeGenerating(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Step identifiers must be unique.');

        new TintAndShades(new CssColor('#336699'), [
            new ColorVariationStep('100', 20, VariationDirection::Lighter),
            new ColorVariationStep('100', 20, VariationDirection::Darker),
        ]);
    }

    public function testItShouldGenerateTheSameColorsOnRepeatedCalls(): void
    {
        $generator = new TintAndShades(new CssColor('#336699'), [
            new ColorVariationStep('100', 20, VariationDirection::Lighter),
            new ColorVariationStep('900', 20, VariationDirection::Darker),
        ]);
        $first = $generator->generate();
        $second = $generator->generate();

        $this->assertSame(\array_keys($first), \array_keys($second));
        $this->assertSame('#5c85ad', (string) $second[100]);
        $this->assertSame('#29527a', (string) $second[900]);
        $this->assertTrue($first[100]->equals($second[100]));
        $this->assertTrue($first[900]->equals($second[900]));
    }

    public function testItShouldWorkWithThePresetFactoryAndThemeJsonSerialization(): void
    {
        $source = new TintAndShades(new CssColor('#336699'), [
            new ColorVariationStep('100', 20, VariationDirection::Lighter),
            new ColorVariationStep('brand', 0, VariationDirection::Darker),
            new ColorVariationStep('900', 20, VariationDirection::Darker),
        ]);
        $factory = new ColorPresetsFromGeneratorFactory($source, 'primary', 'Primary');
        $config = new Config();
        $presets = new Presets();
        $themeJson = new ThemeJson($config, $presets);

        $themeJson->settings()->color()->addColors($factory->create());
        (new PresetsToThemeJson())($config, $presets);

        $this->assertSame([
            ['slug' => 'primary-100', 'name' => 'Primary 100', 'color' => '#5c85ad'],
            ['slug' => 'primary-brand', 'name' => 'Primary brand', 'color' => '#336699'],
            ['slug' => 'primary-900', 'name' => 'Primary 900', 'color' => '#29527a'],
        ], $themeJson->get('settings.color.palette'));
        $this->assertSame(
            'var(--wp--preset--color--primary-100)',
            $presets->get('color.primary-100')->var()
        );
    }
}
