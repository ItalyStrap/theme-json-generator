<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Composer;

use Composer\Plugin\PluginInterface;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Composer\Plugin;
use Prophecy\Argument;

use function codecept_output_dir;

class ComposerPluginTest extends UnitTestCase
{
    protected function makeInstance(): Plugin
    {
        $sut = new Plugin();
        $this->assertInstanceOf(PluginInterface::class, $sut, '');
        return $sut;
    }

    /**
     * @test
     */
    public function itShouldBeInstantiatable()
    {
        $sut = $this->makeInstance();
    }

    /**
     * @test
     */
    public function itShouldNotCreateThemeJsonFileFromRootPackage()
    {
        $this->markTestSkipped('This test needs to be fixed');
        $theme_json_file_path = codecept_output_dir(random_int(0, mt_getrandmax()) . '/vendor');
        $this->config
            ->get(Argument::type('string'))
            ->willReturn($theme_json_file_path);

        $this->rootPackage->getType()->willReturn('wordpress-theme');
        $this->rootPackage->getExtra()->willReturn([
            'theme-json' => [
                'callable' => false,
            ],
        ]);

        $this->expectException(\RuntimeException::class);

        $sut = $this->makeInstance();
        $sut->createThemeJson($this->makeComposer(), $this->makeIo());

        $theme_json_file_path = dirname($theme_json_file_path);
        $this->assertFileNotExists($theme_json_file_path . '/theme.json', '');
    }

    /**
     * @test
     */
    public function itShouldCreateThemeJsonFileFromRootPackage()
    {
        $rand = (string) random_int(0, mt_getrandmax());
        $temp_dir_path = codecept_output_dir($rand . '/vendor');

        $this->composerConfig
            ->get(Argument::type('string'))
            ->willReturn($temp_dir_path);

        $this->rootPackage->getType()->willReturn('wordpress-theme');
        $this->rootPackage->getExtra()->willReturn([
            'theme-json' => [
                'callable' => fn (): array => ['key' => 'value'],
                'path-for-theme-sass'   => 'assets/',
            ],
        ]);

        $sut = $this->makeInstance();
        $sut->createThemeJson($this->makeComposer(), $this->makeIo());

        $theme_json_file_path = dirname($temp_dir_path) . '/theme.json';
        $this->assertFileExists($theme_json_file_path, '');
        $this->assertFileIsReadable($theme_json_file_path, '');
        $this->assertFileIsWritable($theme_json_file_path, '');

        /**
         * @todo QUi il test fa merda, da rifare
         */
//      $theme_scss_file_path = dirname( $temp_dir_path ) . '/theme.scss';
//      $this->assertFileExists( $theme_scss_file_path, '');
//      $this->assertFileIsReadable( $theme_scss_file_path, '');
//      $this->assertFileIsWritable( $theme_scss_file_path, '');

        \unlink($theme_json_file_path);
//      \unlink($theme_scss_file_path);
    }

    /**
     * @test
     */
    public function itShouldCreateThemeJsonFileFromRequiredPackage()
    {
        $this->markTestSkipped('This test needs to be fixed');
        $theme_json_file_path = codecept_output_dir(random_int(0, mt_getrandmax()) .  '/vendor');

        $this->composerConfig
            ->get(Argument::type('string'))
            ->willReturn($theme_json_file_path);

        $this->composer->getRepositoryManager()->willReturn($this->makeRepositoryManager());
        $this->link->getConstraint()->willReturn('dev-master');
        $this->rootPackage->getRequires()->willReturn([ $this->makeLink() ]);
        $this->rootPackage->getType()->willReturn('wordpress-theme');
        $this->rootPackage->getExtra()->willReturn([
            'theme-json' => [
                'callable' => fn (): array => ['key' => 'value'],
            ],
        ]);

        $this->link->getTarget()->willReturn('italystrap/themejsongenerator');
        $this->repositoryManager
            ->findPackage('italystrap/themejsongenerator', 'dev-master')
            ->willReturn($this->makePackage());
//        $this->package->getType()->willReturn('wordpress-theme');
//
//        $this->package->getExtra()->willReturn([
//            'theme-json' => [
//                'callable' => fn(): array => ['key' => 'value'],
//            ],
//        ]);

        $sut = $this->makeInstance();
        $sut->createThemeJson($this->makeComposer(), $this->makeIo());

        $theme_json_file_path = $theme_json_file_path . '/italystrap/themejsongenerator/theme.json';
        $this->assertFileExists($theme_json_file_path, '');
        $this->assertFileIsReadable($theme_json_file_path, '');
        $this->assertFileIsWritable($theme_json_file_path, '');

        \unlink($theme_json_file_path);

        $this->link->getConstraint()->shouldHaveBeenCalled();
        $this->link->getTarget()->shouldHaveBeenCalled();
        $this->repositoryManager
            ->findPackage(Argument::type('string'), Argument::type('string'))
            ->shouldHaveBeenCalled();
        $this->package->getType()->shouldHaveBeenCalled();
        $this->package->getExtra()->shouldHaveBeenCalled();
    }
}
