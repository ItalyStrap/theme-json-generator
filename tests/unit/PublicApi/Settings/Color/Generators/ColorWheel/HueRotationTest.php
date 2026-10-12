<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Generators\ColorWheel;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Container\PresetsToThemeJson;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Factories\ColorPresetsFromGeneratorFactory;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorsGenerator;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorWheel\HueRotation;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\HueRotationStep;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;
use Spatie\Color\Hsla;

final class HueRotationTest extends UnitTestCase
{
    public function testItShouldReturnNoColorsWithoutSteps(): void
    {
        $generator = new HueRotation(new CssColor('#336699'), []);

        $this->assertInstanceOf(ColorsGenerator::class, $generator);
        $this->assertSame([], $generator->generate());
    }

    public function testItShouldGenerateOneColorPerStepInConfiguredOrder(): void
    {
        $baseColor = new CssColor('#336699');
        $colors = (new HueRotation($baseColor, [
            new HueRotationStep('right', 30),
            new HueRotationStep('100', -30),
            new HueRotationStep('complementary', 180),
        ]))->generate();

        $this->assertSame(['right', 100, 'complementary'], \array_keys($colors));
        $this->assertCount(3, $colors);
        $this->assertContainsOnlyInstancesOf(CssColorInterface::class, $colors);
        $this->assertSame('#333399', (string) $colors['right']);
        $this->assertSame('#339999', (string) $colors[100]);
        $this->assertSame('#996633', (string) $colors['complementary']);
        $this->assertSame('#336699', (string) $baseColor);
    }

    public function testItShouldNotAddAnImplicitBaseColorForOneStep(): void
    {
        $colors = (new HueRotation(new CssColor('#336699'), [
            new HueRotationStep('accent', 180),
        ]))->generate();

        $this->assertSame(['accent'], \array_keys($colors));
        $this->assertSame('#996633', (string) $colors['accent']);
    }

    public function testItShouldReturnTheOriginalInstanceForZeroAndFullTurns(): void
    {
        $baseColor = new CssColor(new Hsla(210, 50, 40, 0.123456));
        $colors = (new HueRotation($baseColor, [
            new HueRotationStep('500', 0),
            new HueRotationStep('brand', 0),
            new HueRotationStep('full-turn', 360),
            new HueRotationStep('backwards', -720),
        ]))->generate();

        $this->assertSame([500, 'brand', 'full-turn', 'backwards'], \array_keys($colors));
        foreach ($colors as $color) {
            $this->assertSame($baseColor, $color);
        }
    }

    public function testItShouldWrapPositiveAndNegativeRotations(): void
    {
        $colors = (new HueRotation(new CssColor('hsl(350,50%,40%)'), [
            new HueRotationStep('forward', 30),
            new HueRotationStep('backward', -360),
            new HueRotationStep('many-forward', 750),
            new HueRotationStep('many-backward', -750),
        ]))->generate();

        $this->assertSame('hsl(20,50%,40%)', (string) $colors['forward']);
        $this->assertSame('hsl(350,50%,40%)', (string) $colors['backward']);
        $this->assertSame('hsl(20,50%,40%)', (string) $colors['many-forward']);
        $this->assertSame('hsl(320,50%,40%)', (string) $colors['many-backward']);
    }

    public function testItShouldSupportIntegerLimitsWithoutOverflow(): void
    {
        $colors = (new HueRotation(new CssColor('hsl(350,50%,40%)'), [
            new HueRotationStep('maximum', \PHP_INT_MAX),
            new HueRotationStep('minimum', \PHP_INT_MIN),
        ]))->generate();

        $maximumHue = ((350 + (\PHP_INT_MAX % 360)) % 360 + 360) % 360;
        $minimumHue = ((350 + (\PHP_INT_MIN % 360)) % 360 + 360) % 360;
        $this->assertSame(\sprintf('hsl(%d,50%%,40%%)', $maximumHue), (string) $colors['maximum']);
        $this->assertSame(\sprintf('hsl(%d,50%%,40%%)', $minimumHue), (string) $colors['minimum']);
    }

    public function testItShouldApplyEveryStepToTheOriginalColorAndKeepEqualValues(): void
    {
        $colors = (new HueRotation(new CssColor('#336699'), [
            new HueRotationStep('first', 30),
            new HueRotationStep('second', 30),
            new HueRotationStep('alias', 390),
        ]))->generate();

        $this->assertCount(3, $colors);
        $this->assertSame('#333399', (string) $colors['first']);
        $this->assertSame('#333399', (string) $colors['second']);
        $this->assertSame('#333399', (string) $colors['alias']);
    }

    public function testItShouldKeepAchromaticColorsWithDistinctIdentifiers(): void
    {
        $colors = (new HueRotation(new CssColor('#808080'), [
            new HueRotationStep('first', 30),
            new HueRotationStep('second', 180),
        ]))->generate();

        $this->assertSame(['first', 'second'], \array_keys($colors));
        $this->assertTrue($colors['first']->equals($colors['second']));
    }

    public function testItShouldPreserveTheSourceFormatAndTransparency(): void
    {
        $colors = (new HueRotation(new CssColor('hsla(210,50%,40%,0.5)'), [
            new HueRotationStep('accent', 180),
        ]))->generate();

        $this->assertSame('hsla(30,50%,40%,0.5)', (string) $colors['accent']);
    }

    public function testItShouldRejectDuplicateIdentifiersBeforeGenerating(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Step identifiers must be unique.');

        new HueRotation(new CssColor('#336699'), [
            new HueRotationStep('accent', 30),
            new HueRotationStep('accent', -30),
        ]);
    }

    public function testItShouldGenerateTheSameValuesOnRepeatedCalls(): void
    {
        $generator = new HueRotation(new CssColor('#336699'), [
            new HueRotationStep('accent', 180),
        ]);

        $first = $generator->generate();
        $second = $generator->generate();

        $this->assertSame(\array_keys($first), \array_keys($second));
        $this->assertSame('#996633', (string) $second['accent']);
        $this->assertTrue($first['accent']->equals($second['accent']));
    }

    public function testItShouldSerializePresetsIntoTheThemeJsonPalette(): void
    {
        $baseColor = new CssColor('#336699');
        $generator = new HueRotation($baseColor, [
            new HueRotationStep('brand', 0),
            new HueRotationStep('accent', 180),
        ]);
        $factory = new ColorPresetsFromGeneratorFactory($generator, 'primary', 'Primary');
        $config = new Config();
        $presets = new Presets();
        $themeJson = new ThemeJson($config, $presets);

        $themeJson->settings()->color()->addColors($factory->create());
        (new PresetsToThemeJson())($config, $presets);

        $this->assertSame([
            ['slug' => 'primary-brand', 'name' => 'Primary brand', 'color' => '#336699'],
            ['slug' => 'primary-accent', 'name' => 'Primary accent', 'color' => '#996633'],
        ], $themeJson->get('settings.color.palette'));
        $this->assertSame('var(--wp--preset--color--primary-accent)', $presets->get('color.primary-accent')->var());
        $this->assertSame($baseColor, $presets->get('color.primary-brand')->color());
    }
}
