<?php
declare(strict_types=1);

namespace ItalyStrap\Tests;

use Codeception\Test\Unit;
use Composer\Json\JsonFile;
use ItalyStrap\ThemeJsonGenerator\Generator;

class ThemeJsonGeneratorTest extends Unit {

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

	protected function getInstance(): Generator {
		$sut = new Generator(['ciao'=> 'bello']);
		$this->assertInstanceOf( Generator::class, $sut, '' );
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
		$expected = '{"ciao": "bello"}';

		$this->assertJson( $sut->toJson(), '' );
		$this->assertJsonStringEqualsJsonString( $expected, $sut->toJson(), '' );
	}

	/**
	 * @test
	 */
	public function itShouldFileExists() {

		$input_data = require \codecept_data_dir('fixtures/input-data.php');
		$theme_json = \codecept_output_dir('theme.jsons');

//		$file = new \SplFileObject( $theme_json, 'w' );
//		$json = JsonFile::encode( $input_data );
//		$is_file_ok = $file->fwrite( $json );
//
//		if ( ! $is_file_ok ) {
//			throw new \RuntimeException('Failed to create theme.json file', \intval( $is_file_ok  ));
//		}

//		$json = new \Composer\Json\JsonFile( $theme_json );
//		$json->write( $input_data );
//
//		$this->assertFileExists( $theme_json, '');
//		$this->assertFileIsReadable( $theme_json, '');
//		$this->assertFileIsWritable( $theme_json, '');
//
//		$file = null;
//		\unlink($theme_json);
	}
}
