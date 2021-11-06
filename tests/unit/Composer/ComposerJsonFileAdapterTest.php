<?php
declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Composer;

use Codeception\Test\Unit;
use Composer\Json\JsonFile;
use ItalyStrap\ThemeJsonGenerator\Composer\ComposerJsonFileAdapter;
use Prophecy\Argument;
use Prophecy\Prophet;
use UnitTester;

class ComposerJsonFileAdapterTest extends Unit {

	/**
	 * @var UnitTester
	 */
	protected $tester;

	/**
	 * @var Prophet
	 */
	private $prophet;

	/**
	 * @var JsonFile
	 */
	private $jsonFile;

	/**
	 * @var array
	 */
	private $input_data;

	/**
	 * @return JsonFile
	 */
	public function getJsonFile(): JsonFile {
		return $this->jsonFile->reveal();
	}

	// phpcs:ignore
	protected function _before() {
		$this->prophet = new Prophet;
		$this->jsonFile = $this->prophet->prophesize( JsonFile::class );
		$this->input_data = require \codecept_data_dir('fixtures/input-data.php');
	}

	/**
	 * @return array
	 */
	public function getInputData(): array {
		return $this->input_data;
	}

	// phpcs:ignore
	protected function _after() {
		$this->prophet->checkPredictions();
	}

	protected function getInstance(): ComposerJsonFileAdapter {
		$sut = new ComposerJsonFileAdapter( $this->getJsonFile() );
		$this->assertInstanceOf( ComposerJsonFileAdapter::class, $sut, '' );
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
	 * @throws \Exception
	 */
	public function itShouldWrite() {
		$sut = $this->getInstance();
		$sut->write( $this->getInputData() );
		$this->jsonFile->write( Argument::type('array'), Argument::any() )->shouldHaveBeenCalled();
	}
}
