<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Generators\Monochromatic;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorsGenerator;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\Monochromatic\HslLightness;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\ColorVariationStep;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\VariationDirection;
use Spatie\Color\Hsla;

final class HslLightnessTest extends UnitTestCase
{
    public function testItShouldReturnNoColorsWithoutSteps(): void
    {
        $generator = new HslLightness(new CssColor('#336699'), []);

        $this->assertInstanceOf(ColorsGenerator::class, $generator);
        $this->assertSame([], $generator->generate());
    }

    public function testItShouldGenerateOneColorPerStepUsingHslLightness(): void
    {
        $baseColor = new CssColor('#336699');
        $colors = (new HslLightness($baseColor, [
            new ColorVariationStep('100', 20, VariationDirection::Lighter),
            new ColorVariationStep('900', 20, VariationDirection::Darker),
        ]))->generate();

        $this->assertSame([100, 900], \array_keys($colors));
        $this->assertCount(2, $colors);
        $this->assertContainsOnlyInstancesOf(CssColorInterface::class, $colors);
        $this->assertSame('#6699cc', (string) $colors[100]);
        $this->assertSame('#1a334d', (string) $colors[900]);
        $this->assertSame('#336699', (string) $baseColor);
    }

    public function testItShouldNotAddAnImplicitBaseColorForOneStep(): void
    {
        $colors = (new HslLightness(new CssColor('#336699'), [
            new ColorVariationStep('highlight', 20, VariationDirection::Lighter),
        ]))->generate();

        $this->assertSame(['highlight'], \array_keys($colors));
        $this->assertSame('#6699cc', (string) $colors['highlight']);
    }

    public function testItShouldPreserveConfiguredOrderAndSupportUnequalDirectionCounts(): void
    {
        $colors = (new HslLightness(new CssColor('#336699'), [
            new ColorVariationStep('shadow', 20, VariationDirection::Darker),
            new ColorVariationStep('highlight', 20, VariationDirection::Lighter),
            new ColorVariationStep('subtle', 10, VariationDirection::Lighter),
        ]))->generate();

        $this->assertSame(['shadow', 'highlight', 'subtle'], \array_keys($colors));
        $this->assertCount(3, $colors);
        $this->assertSame('#1a334d', (string) $colors['shadow']);
        $this->assertSame('#6699cc', (string) $colors['highlight']);
        $this->assertSame('#4080bf', (string) $colors['subtle']);
    }

    public function testItShouldReturnTheOriginalInstanceForZeroInEitherDirection(): void
    {
        $baseColor = new CssColor(new Hsla(210, 50, 40, 0.123456));
        $colors = (new HslLightness($baseColor, [
            new ColorVariationStep('500', 0, VariationDirection::Lighter),
            new ColorVariationStep('brand', 0, VariationDirection::Darker),
        ]))->generate();

        $this->assertSame([500, 'brand'], \array_keys($colors));
        $this->assertSame($baseColor, $colors[500]);
        $this->assertSame($baseColor, $colors['brand']);
    }

    public function testItShouldApplyEveryStepToTheOriginalColor(): void
    {
        $colors = (new HslLightness(new CssColor('#336699'), [
            new ColorVariationStep('first', 20, VariationDirection::Lighter),
            new ColorVariationStep('second', 20, VariationDirection::Lighter),
        ]))->generate();

        $this->assertSame('#6699cc', (string) $colors['first']);
        $this->assertSame('#6699cc', (string) $colors['second']);
    }

    public function testItShouldClampLightnessAndKeepExplicitDuplicateColors(): void
    {
        $colors = (new HslLightness(new CssColor('#336699'), [
            new ColorVariationStep('white', 100, VariationDirection::Lighter),
            new ColorVariationStep('also-white', 80, VariationDirection::Lighter),
            new ColorVariationStep('black', 100, VariationDirection::Darker),
        ]))->generate();

        $this->assertCount(3, $colors);
        $this->assertSame('#ffffff', (string) $colors['white']);
        $this->assertSame('#ffffff', (string) $colors['also-white']);
        $this->assertSame('#000000', (string) $colors['black']);
    }

    public function testItShouldUseTheExistingModifierRoundingForFractionalAmounts(): void
    {
        $colors = (new HslLightness(new CssColor('#336699'), [
            new ColorVariationStep('fractional', 2.5, VariationDirection::Lighter),
        ]))->generate();

        $this->assertSame('#376ea4', (string) $colors['fractional']);
    }

    public function testItShouldPreserveTheSourceFormatAndTransparency(): void
    {
        $colors = (new HslLightness(new CssColor('hsla(210,50%,40%,0.5)'), [
            new ColorVariationStep('lighter', 20, VariationDirection::Lighter),
            new ColorVariationStep('darker', 20, VariationDirection::Darker),
        ]))->generate();

        $this->assertSame('hsla(210,50%,60%,0.5)', (string) $colors['lighter']);
        $this->assertSame('hsla(210,50%,20%,0.5)', (string) $colors['darker']);
    }

    public function testItShouldRejectDuplicateIdentifiersBeforeGenerating(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Step identifiers must be unique.');

        new HslLightness(new CssColor('#336699'), [
            new ColorVariationStep('100', 20, VariationDirection::Lighter),
            new ColorVariationStep('100', 20, VariationDirection::Darker),
        ]);
    }

    public function testItShouldGenerateTheSameValuesOnRepeatedCalls(): void
    {
        $generator = new HslLightness(new CssColor('#336699'), [
            new ColorVariationStep('100', 20, VariationDirection::Lighter),
            new ColorVariationStep('900', 20, VariationDirection::Darker),
        ]);

        $first = $generator->generate();
        $second = $generator->generate();

        $this->assertSame(\array_keys($first), \array_keys($second));
        $this->assertSame('#6699cc', (string) $second[100]);
        $this->assertSame('#1a334d', (string) $second[900]);
        $this->assertTrue($first[100]->equals($second[100]));
        $this->assertTrue($first[900]->equals($second[900]));
    }
}
