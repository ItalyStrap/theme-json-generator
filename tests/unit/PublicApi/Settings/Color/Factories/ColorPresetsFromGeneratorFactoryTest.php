<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Color\Factories;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Container\PresetsToThemeJson;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Color;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Factories\ColorPresetsFromGeneratorFactory;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\AchromaticColors;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\ColorsGenerator;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Generators\Monochromatic\HslLightness;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\ColorVariationStep;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\VariationDirection;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class ColorPresetsFromGeneratorFactoryTest extends UnitTestCase
{
    public function testItShouldWrapColorsPreservingKeysOrderAndInstances(): void
    {
        $darkColor = new CssColor('#1a334d');
        $lightColor = new CssColor('#6699cc');
        $source = $this->prophesize(ColorsGenerator::class);
        $source->generate()->willReturn([900 => $darkColor, 100 => $lightColor])->shouldBeCalledOnce();

        $presets = (new ColorPresetsFromGeneratorFactory($source->reveal(), 'primary', 'Primary'))->create();

        $this->assertSame([900, 100], \array_keys($presets));
        $this->assertContainsOnlyInstancesOf(Color::class, $presets);
        $this->assertSame($darkColor, $presets[900]->color());
        $this->assertSame($lightColor, $presets[100]->color());
        $this->assertSame([
            'slug' => 'primary-900',
            'name' => 'Primary 900',
            'color' => '#1a334d',
        ], $presets[900]->toArray());
        $this->assertSame([
            'slug' => 'primary-100',
            'name' => 'Primary 100',
            'color' => '#6699cc',
        ], $presets[100]->toArray());
    }

    public function testItShouldUseCustomIdentifiersAndIndependentPrefixes(): void
    {
        $source = $this->generatorFrom(['highlight' => new CssColor('#6699cc')]);

        $presets = (new ColorPresetsFromGeneratorFactory(
            $source,
            slugPrefix: 'brand-primary',
            namePrefix: 'Brand Primary'
        ))->create();

        $this->assertSame(['highlight'], \array_keys($presets));
        $this->assertSame([
            'slug' => 'brand-primary-highlight',
            'name' => 'Brand Primary highlight',
            'color' => '#6699cc',
        ], $presets['highlight']->toArray());
    }

    public function testItShouldSupportEmptyPrefixesWithoutAddingSeparators(): void
    {
        $source = $this->generatorFrom(['brand' => new CssColor('#336699')]);

        $presets = (new ColorPresetsFromGeneratorFactory($source, '', ''))->create();

        $this->assertSame([
            'slug' => 'brand',
            'name' => 'brand',
            'color' => '#336699',
        ], $presets['brand']->toArray());
    }

    public function testItShouldHandleEachEmptyPrefixIndependently(): void
    {
        $source = $this->generatorFrom([100 => new CssColor('#6699cc')]);

        $withoutSlugPrefix = (new ColorPresetsFromGeneratorFactory($source, '', 'Primary'))->create();
        $withoutNamePrefix = (new ColorPresetsFromGeneratorFactory($source, 'primary', ''))->create();

        $this->assertSame('100', $withoutSlugPrefix[100]->slug());
        $this->assertSame('Primary 100', $withoutSlugPrefix[100]->toArray()['name']);
        $this->assertSame('primary-100', $withoutNamePrefix[100]->slug());
        $this->assertSame('100', $withoutNamePrefix[100]->toArray()['name']);
    }

    public function testItShouldReturnNoPresetsWithoutGeneratedColors(): void
    {
        $source = new HslLightness(new CssColor('#336699'), []);

        $this->assertSame([], (new ColorPresetsFromGeneratorFactory($source, 'primary', 'Primary'))->create());
    }

    public function testItShouldWorkWithOtherGeneratorsIncludingIdentifierZero(): void
    {
        $presets = (new ColorPresetsFromGeneratorFactory(new AchromaticColors(), 'neutral', 'Neutral'))->create();

        $this->assertSame([0, 1], \array_keys($presets));
        $this->assertSame([
            'slug' => 'neutral-0',
            'name' => 'Neutral 0',
            'color' => '#000000',
        ], $presets[0]->toArray());
        $this->assertSame([
            'slug' => 'neutral-1',
            'name' => 'Neutral 1',
            'color' => '#ffffff',
        ], $presets[1]->toArray());
    }

    public function testItShouldKeepEqualColorsWithDistinctIdentifiers(): void
    {
        $baseColor = new CssColor('#336699');
        $source = new HslLightness($baseColor, [
            new ColorVariationStep('500', 0, VariationDirection::Lighter),
            new ColorVariationStep('brand', 0, VariationDirection::Darker),
        ]);

        $presets = (new ColorPresetsFromGeneratorFactory($source, 'primary', 'Primary'))->create();

        $this->assertSame([500, 'brand'], \array_keys($presets));
        $this->assertSame('primary-500', $presets[500]->slug());
        $this->assertSame('primary-brand', $presets['brand']->slug());
        $this->assertSame($baseColor, $presets[500]->color());
        $this->assertSame($baseColor, $presets['brand']->color());
    }

    public function testItShouldUseExistingPresetValidationForInvalidSlugs(): void
    {
        $source = $this->generatorFrom([100 => new CssColor('#6699cc')]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid preset slug "brand primary-100"');

        (new ColorPresetsFromGeneratorFactory($source, 'brand primary', 'Primary'))->create();
    }

    public function testItShouldNotSilentlyRewriteInvalidIdentifiers(): void
    {
        $source = $this->generatorFrom(['light blue' => new CssColor('#6699cc')]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid preset slug "primary-light blue"');

        (new ColorPresetsFromGeneratorFactory($source, 'primary', 'Primary'))->create();
    }

    public function testItShouldCreateTheSamePresetsOnRepeatedCalls(): void
    {
        $factory = new ColorPresetsFromGeneratorFactory(new AchromaticColors(), 'neutral', 'Neutral');
        $first = $factory->create();
        $second = $factory->create();

        $this->assertSame(\array_keys($first), \array_keys($second));
        $this->assertSame($first[0]->toArray(), $second[0]->toArray());
        $this->assertSame($first[1]->toArray(), $second[1]->toArray());
    }

    public function testItShouldSerializeCreatedPresetsIntoTheThemeJsonPalette(): void
    {
        $baseColor = new CssColor('#336699');
        $source = new HslLightness($baseColor, [
            new ColorVariationStep('100', 20, VariationDirection::Lighter),
            new ColorVariationStep('500', 0, VariationDirection::Lighter),
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
            ['slug' => 'primary-100', 'name' => 'Primary 100', 'color' => '#6699cc'],
            ['slug' => 'primary-500', 'name' => 'Primary 500', 'color' => '#336699'],
            ['slug' => 'primary-brand', 'name' => 'Primary brand', 'color' => '#336699'],
            ['slug' => 'primary-900', 'name' => 'Primary 900', 'color' => '#1a334d'],
        ], $themeJson->get('settings.color.palette'));
        $this->assertSame(
            'var(--wp--preset--color--primary-100)',
            $presets->get('color.primary-100')->var()
        );
        $this->assertSame($baseColor, $presets->get('color.primary-brand')->color());
    }

    /**
     * @param array<array-key, CssColorInterface> $colors
     */
    private function generatorFrom(array $colors): ColorsGenerator
    {
        $generator = $this->prophesize(ColorsGenerator::class);
        $generator->generate()->willReturn($colors);

        return $generator->reveal();
    }
}
