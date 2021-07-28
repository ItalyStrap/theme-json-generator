<?php
declare(strict_types=1);

namespace ItalyStrap\Tests;

use Codeception\Test\Unit;
use ItalyStrap\ThemeJsonGenerator\JsonFileBuilder;

class ThemeJsonGeneratorTest extends Unit {

	/**
	 * @var \UnitTester
	 */
	protected $tester;

	/**
	 * @var string
	 */
	private $theme_json_path;

	// phpcs:ignore
	protected function _before() {
	}

	// phpcs:ignore
	protected function _after() {
	}

	protected function getInstance(): JsonFileBuilder {
		$this->theme_json_path = \codecept_output_dir(\rand() . '/theme.json');
		$sut = new JsonFileBuilder( $this->theme_json_path );
		$this->assertInstanceOf( JsonFileBuilder::class, $sut, '' );
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
	public function itShouldReturnValidJson() {
		$sut = $this->getInstance();
		$expected = '{"key": "value"}';


//		$callable = function ( array $args ): array {
//			return $this->getInputData();
//		};

		$callable = function ( string $path ): array {
			$this->assertStringContainsString($path, $this->theme_json_path, '');
			return [
				'key'	=> 'value',
			];
		};

		$sut->build( $callable );

		$this->assertJsonStringEqualsJsonFile($this->theme_json_path, $expected, '');
	}
}
