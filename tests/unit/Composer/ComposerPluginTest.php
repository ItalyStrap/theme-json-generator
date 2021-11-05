<?php
declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Composer;

use Codeception\Test\Unit;
use Composer\Composer;
use Composer\Config;
use Composer\IO\IOInterface;
use Composer\Package\Link;
use Composer\Package\PackageInterface;
use Composer\Package\RootPackageInterface;
use Composer\Plugin\PluginInterface;
use Composer\Repository\RepositoryManager;
use ItalyStrap\ThemeJsonGenerator\ComposerPlugin;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;
use UnitTester;
use function codecept_output_dir;

class ComposerPluginTest extends Unit {

	/**
	 * @var UnitTester
	 */
	protected $tester;

	/**
	 * @var Prophet
	 */
	private $prophet;

	/**
	 * @var ObjectProphecy
	 */
	private $composer;

	/**
	 * @var ObjectProphecy
	 */
	private $io;

	/**
	 * @var ObjectProphecy
	 */
	private $config;

	/**
	 * @var ObjectProphecy
	 */
	private $rootPackage;

	/**
	 * @var Link
	 */
	private $link;

	/**
	 * @var ObjectProphecy
	 */
	private $repositoryManager;

	/**
	 * @var ObjectProphecy
	 */
	private $package;

	/**
	 * @return Composer
	 */
	public function getComposer(): Composer {
		return $this->composer->reveal();
	}

	/**
	 * @return IOInterface
	 */
	public function getIo(): IOInterface {
		return $this->io->reveal();
	}

	/**
	 * @return Config
	 */
	public function getConfig(): Config {
		return $this->config->reveal();
	}

	/**
	 * @return RootPackageInterface
	 */
	public function getRootPackage(): RootPackageInterface {
		return $this->rootPackage->reveal();
	}

	/**
	 * @return Link
	 */
	public function getLink(): Link {
		return $this->link->reveal();
	}

	/**
	 * @return RepositoryManager
	 */
	public function getRepositoryManager(): RepositoryManager {
		return $this->repositoryManager->reveal();
	}

	/**
	 * @return PackageInterface
	 */
	public function getPackage(): PackageInterface {
		return $this->package->reveal();
	}

	// phpcs:ignore
	protected function _before() {
		$this->prophet = new Prophet;
		$this->composer = $this->prophet->prophesize( Composer::class );
		$this->io = $this->prophet->prophesize( IOInterface::class );
		$this->config = $this->prophet->prophesize( Config::class );
		$this->rootPackage = $this->prophet->prophesize( RootPackageInterface::class );
		$this->link = $this->prophet->prophesize( Link::class );
		$this->repositoryManager = $this->prophet->prophesize( RepositoryManager::class );
		$this->package = $this->prophet->prophesize( PackageInterface::class );

		$this->composer->getConfig()->willReturn( $this->getConfig() );
		$this->composer->getPackage()->willReturn( $this->getRootPackage() );
	}

	// phpcs:ignore
	protected function _after() {
	}

	protected function getInstance(): ComposerPlugin {
		$sut = new ComposerPlugin();
		$this->assertInstanceOf( ComposerPlugin::class, $sut, '' );
//		$this->assertInstanceOf( EventSubscriberInterface::class, $sut, '' );
		$this->assertInstanceOf( PluginInterface::class, $sut, '' );
		return $sut;
	}

	/**
	 * @test
	 */
	public function itShouldBeInstantiatable() {
		$sut = $this->getInstance();
	}

	/**
	 * @test
	 */
	public function itShouldNotCreateThemeJsonFileFromRootPackage() {
		$theme_json_file_path = codecept_output_dir(\rand() . '/vendor');
		$this->config
			->get( Argument::type('string') )
			->willReturn( $theme_json_file_path );

		$this->rootPackage->getType()->willReturn('wordpress-theme');
		$this->rootPackage->getExtra()->willReturn([
			'theme-json' => [
				'callable' => false,
			],
		]);

		$this->expectException( \RuntimeException::class );

		$sut = $this->getInstance();
		$sut->createThemeJson( $this->getComposer(), $this->getIo() );

		$theme_json_file_path = dirname( $theme_json_file_path );
		$this->assertFileNotExists( $theme_json_file_path . '/theme.json', '');
	}

	/**
	 * @test
	 */
	public function itShouldCreateThemeJsonFileFromRootPackage() {
		$rand = (string) \rand();
		$temp_dir_path = codecept_output_dir( $rand . '/vendor' );

		$this->config
			->get( Argument::type('string') )
			->willReturn( $temp_dir_path );

		$this->rootPackage->getType()->willReturn('wordpress-theme');
		$this->rootPackage->getExtra()->willReturn([
			'theme-json' => [
				'callable' => fn (): array => ['key' => 'value'],
				'path-for-theme-sass'	=> 'assets/',
			],
		]);

		$sut = $this->getInstance();
		$sut->createThemeJson( $this->getComposer(), $this->getIo() );

		$theme_json_file_path = dirname( $temp_dir_path ) . '/theme.json';
		$this->assertFileExists( $theme_json_file_path, '');
		$this->assertFileIsReadable( $theme_json_file_path, '');
		$this->assertFileIsWritable( $theme_json_file_path, '');

		/**
		 * @todo QUi il test fa merda, da rifare
		 */
//		$theme_scss_file_path = dirname( $temp_dir_path ) . '/theme.scss';
//		$this->assertFileExists( $theme_scss_file_path, '');
//		$this->assertFileIsReadable( $theme_scss_file_path, '');
//		$this->assertFileIsWritable( $theme_scss_file_path, '');

		\unlink($theme_json_file_path);
//		\unlink($theme_scss_file_path);
	}

	/**
	 * @test
	 */
	public function itShouldCreateThemeJsonFileFromRequiredPackage() {
		$theme_json_file_path = codecept_output_dir(\rand() .  '/vendor');

		$this->config
			->get( Argument::type('string') )
			->willReturn( $theme_json_file_path );

		$this->composer->getRepositoryManager()->willReturn( $this->getRepositoryManager() );
		$this->link->getConstraint()->willReturn( 'dev-master' );
		$this->rootPackage->getRequires()->willReturn( [ $this->getLink() ] );

		$this->link->getTarget()->willReturn( 'italystrap/themejsongenerator' );
		$this->repositoryManager
			->findPackage( 'italystrap/themejsongenerator', 'dev-master' )
			->willReturn( $this->getPackage() );
		$this->package->getType()->willReturn( 'wordpress-theme' );

		$this->package->getExtra()->willReturn([
			'theme-json' => [
				'callable' => function (): array {
					return ['key' => 'value'];
				},
			],
		]);

		$sut = $this->getInstance();
		$sut->createThemeJson( $this->getComposer(), $this->getIo() );

		$theme_json_file_path = $theme_json_file_path . '/italystrap/themejsongenerator/theme.json';
		$this->assertFileExists( $theme_json_file_path, '');
		$this->assertFileIsReadable( $theme_json_file_path, '');
		$this->assertFileIsWritable( $theme_json_file_path, '');

		\unlink($theme_json_file_path);

		$this->link->getConstraint()->shouldHaveBeenCalled();
		$this->link->getTarget()->shouldHaveBeenCalled();
		$this->repositoryManager
			->findPackage( Argument::type('string'), Argument::type('string'))
			->shouldHaveBeenCalled();
		$this->package->getType()->shouldHaveBeenCalled();
		$this->package->getExtra()->shouldHaveBeenCalled();
	}
}
