<?php
declare(strict_types=1);

namespace ItalyStrap\Tests;

use Codeception\Test\Unit;
use Composer\Json\JsonFile;
use ItalyStrap\ThemeJsonGenerator\ComposerFileJsonAdapter;
use Prophecy\Argument;
use Prophecy\Prophet;
use UnitTester;

class ComposerFileJsonAdapterTest extends Unit
{
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

	// phpcs:ignore
	protected function _after() {
		$this->prophet->checkPredictions();
	}

	protected function getInstance(): ComposerFileJsonAdapter {
		$sut = new ComposerFileJsonAdapter( $this->getJsonFile() );
		$this->assertInstanceOf( ComposerFileJsonAdapter::class, $sut, '' );
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
		$sut->write( $this->input_data );
		$this->jsonFile->write( Argument::type('array'), Argument::any() )->shouldHaveBeenCalled();
	}
}
