<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

use Codeception\Test\Unit;
use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Palette;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\BoxShadow;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\ColorInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\GradientInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetsInterface;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\FilesFinder;
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

    protected array $input_data;

    protected string $color;

    protected string $base_color = '';

    protected function inputData(): array
    {
        return $this->input_data;
    }

    protected ObjectProphecy $item;

    protected function makeItem(): PresetInterface
    {
        return $this->item->reveal();
    }

    protected ObjectProphecy $config;

    public function makeConfig(): ConfigInterface
    {
        return $this->config->reveal();
    }

    protected ObjectProphecy $colorInfo;

    protected function makeColorInfo(): ColorInterface
    {
        return $this->colorInfo->reveal();
    }

    protected ObjectProphecy $gradient;

    protected function makeGradient(): GradientInterface
    {
        return $this->gradient->reveal();
    }

    protected ObjectProphecy $boxShadow;

    protected function makeBoxShadow(): BoxShadow
    {
        return $this->boxShadow->reveal();
    }

    protected ObjectProphecy $palette;

    protected function makePalette(): Palette
    {
        return $this->palette->reveal();
    }

    protected ObjectProphecy $dispatcher;

    protected function makeDispatcher(): EventDispatcherInterface
    {
        return $this->dispatcher->reveal();
    }

    protected ObjectProphecy $filesFinder;

    protected function makeFilesFinder(): FilesFinder
    {
        return $this->filesFinder->reveal();
    }

    protected ObjectProphecy $validator;

    protected function makeValidator(): Validator
    {
        return $this->validator->reveal();
    }

    protected ObjectProphecy $compiler;

    protected function makeCompiler(): Compiler
    {
        return $this->compiler->reveal();
    }

    protected ObjectProphecy $presets;

    protected function makePresets(): PresetsInterface
    {
        return $this->presets->reveal();
    }

    // phpcs:ignore -- Method from Codeception
    protected function _before()
    {
        $this->item = $this->prophesize(PresetInterface::class);
        $this->colorInfo = $this->prophesize(ColorInterface::class);
        $this->gradient = $this->prophesize(GradientInterface::class);
        $this->boxShadow = $this->prophesize(BoxShadow::class);
        $this->palette = $this->prophesize(Palette::class);

        $this->config = $this->prophesize(ConfigInterface::class);
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
    protected function _after()
    {
        $this->getProphet()->checkPredictions();
    }
}
