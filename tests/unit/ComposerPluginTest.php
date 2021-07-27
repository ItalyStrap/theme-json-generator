<?php
declare(strict_types=1);

namespace ItalyStrap\Tests;

use Codeception\Test\Unit;
use ItalyStrap\ThemeJsonGenerator\ComposerPlugin;

class ComposerPluginTest extends Unit {

	/**
	 * @var \UnitTester
	 */
	protected $tester;

	// phpcs:ignore
	protected function _before() {
	}

	// phpcs:ignore
	protected function _after() {
	}

	protected function getInstance(): ComposerPlugin {
		$sut = new ComposerPlugin();
		$this->assertInstanceOf( ComposerPlugin::class, $sut, '' );
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
//	public function itShouldBeInstantiatableefwewe() {
//		$sut = $this->getInstance();
//		$sut->createThemeJson();
//	}
}
