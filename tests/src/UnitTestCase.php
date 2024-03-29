<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

use Codeception\Test\Unit;
use Composer\Composer;
use Composer\Config;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Composer\Package\Link;
use Composer\Package\PackageInterface;
use Composer\Package\RootPackageInterface;
use Composer\Repository\RepositoryManager;
use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Palette;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\ColorInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\GradientInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetInterface;
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

    protected ObjectProphecy $jsonFile;

    protected function makeJsonFile(): JsonFile
    {
        return $this->jsonFile->reveal();
    }

    protected ObjectProphecy $composer;

    protected function makeComposer(): Composer
    {
        return $this->composer->reveal();
    }

    protected ObjectProphecy $composerConfig;

    protected function makeComposerConfig(): Config
    {
        return $this->composerConfig->reveal();
    }

    protected ObjectProphecy $io;

    protected function makeIo(): IOInterface
    {
        return $this->io->reveal();
    }

    protected ObjectProphecy $rootPackage;

    protected function makeRootPackage(): RootPackageInterface
    {
        return $this->rootPackage->reveal();
    }

    protected ObjectProphecy $link;

    protected function makeLink(): Link
    {
        return $this->link->reveal();
    }

    protected ObjectProphecy $repositoryManager;

    protected function makeRepositoryManager(): RepositoryManager
    {
        return $this->repositoryManager->reveal();
    }

    protected ObjectProphecy $package;

    protected function makePackage(): PackageInterface
    {
        return $this->package->reveal();
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

    // phpcs:ignore -- Method from Codeception
    protected function _before()
    {
        $this->item = $this->prophesize(PresetInterface::class);
        $this->colorInfo = $this->prophesize(ColorInterface::class);
        $this->gradient = $this->prophesize(GradientInterface::class);
        $this->palette = $this->prophesize(Palette::class);

        $this->config = $this->prophesize(ConfigInterface::class);
        $this->jsonFile = $this->prophesize(JsonFile::class);
        $this->composer = $this->prophesize(Composer::class);
        $this->composerConfig = $this->prophesize(Config::class);
        $this->io = $this->prophesize(IOInterface::class);
        $this->rootPackage = $this->prophesize(RootPackageInterface::class);
        $this->link = $this->prophesize(Link::class);
        $this->repositoryManager = $this->prophesize(RepositoryManager::class);
        $this->package = $this->prophesize(PackageInterface::class);
        $this->dispatcher = $this->prophesize(EventDispatcherInterface::class);
        $this->filesFinder = $this->prophesize(FilesFinder::class);
        $this->validator = $this->prophesize(Validator::class);
        $this->compiler = $this->prophesize(Compiler::class);

        $this->composer->getConfig()->willReturn($this->makeComposerConfig());
        $this->composer->getPackage()->willReturn($this->makeRootPackage());

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
