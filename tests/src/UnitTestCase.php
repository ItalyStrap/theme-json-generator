<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

use Codeception\Test\Unit;
use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Filesystem\FilesFinder;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Color;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\GradientInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetsInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Shadow\Utilities\BoxShadow;
use JsonSchema\Validator;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\EventDispatcher\EventDispatcherInterface;
use ScssPhp\ScssPhp\Compiler;
use UnitTester;

class UnitTestCase extends Unit
{
    use ProphecyTrait;

    protected UnitTester $tester;

    /** @var array<string, mixed> */
    protected array $input_data;

    protected string $color;

    protected string $base_color = '';

    /** @return array<string, mixed> */
    protected function inputData(): array
    {
        return $this->input_data;
    }

    /** @var ObjectProphecy<PresetInterface> */
    protected ObjectProphecy $item;

    protected function makeItem(): PresetInterface
    {
        return $this->item->reveal();
    }

    /** @var ObjectProphecy<ConfigInterface<array-key, mixed>> */
    protected ObjectProphecy $config;

    /** @return ConfigInterface<array-key, mixed> */
    public function makeConfig(): ConfigInterface
    {
        return $this->config->reveal();
    }

    /** @var ObjectProphecy<CssColorInterface> */
    protected ObjectProphecy $cssColor;

    protected function makeCssColor(): CssColorInterface
    {
        return $this->cssColor->reveal();
    }

    /** @var ObjectProphecy<GradientInterface> */
    protected ObjectProphecy $gradient;

    protected function makeGradient(): GradientInterface
    {
        return $this->gradient->reveal();
    }

    /** @var ObjectProphecy<BoxShadow> */
    protected ObjectProphecy $boxShadow;

    protected function makeBoxShadow(): BoxShadow
    {
        return $this->boxShadow->reveal();
    }

    /** @var ObjectProphecy<Color> */
    protected ObjectProphecy $palette;

    protected function makePalette(): Color
    {
        return $this->palette->reveal();
    }

    /** @var ObjectProphecy<EventDispatcherInterface> */
    protected ObjectProphecy $dispatcher;

    protected function makeDispatcher(): EventDispatcherInterface
    {
        return $this->dispatcher->reveal();
    }

    /** @var ObjectProphecy<FilesFinder> */
    protected ObjectProphecy $filesFinder;

    protected function makeFilesFinder(): FilesFinder
    {
        return $this->filesFinder->reveal();
    }

    /** @var ObjectProphecy<Validator> */
    protected ObjectProphecy $validator;

    protected function makeValidator(): Validator
    {
        return $this->validator->reveal();
    }

    /** @var ObjectProphecy<Compiler> */
    protected ObjectProphecy $compiler;

    protected function makeCompiler(): Compiler
    {
        return $this->compiler->reveal();
    }

    /** @var ObjectProphecy<PresetsInterface> */
    protected ObjectProphecy $presets;

    protected function makePresets(): PresetsInterface
    {
        return $this->presets->reveal();
    }

    /**
     * @template TPreset of PresetInterface
     * @param array<array-key, string|int>|string $key
     * @param class-string<TPreset> $presetClass
     * @return TPreset
     */
    protected function presetFrom(
        PresetsInterface $presets,
        array|string $key,
        string $presetClass
    ): PresetInterface {
        $preset = $presets->get($key);
        $this->assertInstanceOf($presetClass, $preset);

        return $preset;
    }

    // phpcs:ignore -- Method from Codeception
    protected function _before(): void
    {
        $this->item = $this->prophesize(PresetInterface::class);
        $this->cssColor = $this->prophesize(CssColorInterface::class);
        $this->gradient = $this->prophesize(GradientInterface::class);
        $this->boxShadow = $this->prophesize(BoxShadow::class);
        $this->palette = $this->prophesize(Color::class);

        /** @var ObjectProphecy<ConfigInterface<array-key, mixed>> $config */
        $config = $this->prophesize(ConfigInterface::class);
        $this->config = $config;
        $this->dispatcher = $this->prophesize(EventDispatcherInterface::class);
        $this->filesFinder = $this->prophesize(FilesFinder::class);
        $this->validator = $this->prophesize(Validator::class);
        $this->compiler = $this->prophesize(Compiler::class);
        $this->presets = $this->prophesize(PresetsInterface::class);

        $this->input_data = require \codecept_data_dir('fixtures/input-data.php');
        $this->color = '#000000';
        $this->base_color = '#000000';
    }

    // phpcs:ignore -- Method from Codeception
    protected function _after(): void
    {
        $this->getProphet()->checkPredictions();
    }
}
