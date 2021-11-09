<?php
declare(strict_types=1);

namespace ItalyStrap\Tests;

use Codeception\Test\Unit;
use ItalyStrap\ThemeJsonGenerator\Handler\ColorValue;
use UnitTester;

class ColorValueTest extends Unit {

	use BaseUnitTrait;

	/**
	 * @var UnitTester
	 */
	protected $tester;

	private $color;

	// phpcs:ignore
	protected function _before() {
		$this->color = '#000000';
	}

	protected function getInstance() {
		$sut = new ColorValue( $this->color );
		return $sut;
	}

	public function colorProvider() {
		yield [
			'#ffc107', // HEX
			'rgb(255,193,7)', // RGB
			'hsla(45,100%,51%,1)', // HSLA
		];

		yield [
			'#dc3545', // HEX
			'rgb(220,53,69)', // RGB
			'hsla(354,70%,54%,1)', // HSLA
		];

		yield [
			'#532952', // HEX
			'rgb(83,41,82)', // RGB
			'hsla(301,34%,24%,1)', // HSLA
		];

		yield [
			'#512952', // HEX
			'rgb(81,41,82)', // RGB
			'hsla(299,33%,24%,1)', // HSLA
		];

		yield [
			'#faffff', // HEX
			'rgb(250,255,255)', // RGB
			'hsla(180,100%,99%,1)', // HSLA
		];

		yield [
			'#feffff', // HEX
			'rgb(254,255,255)', // RGB
			'hsla(180,100%,100%,1)', // HSLA
		];

		yield [
			'#fefeff', // HEX
			'rgb(254,254,255)', // RGB
			'hsla(240,100%,100%,1)', // HSLA
		];

		yield [
			'#fefefd', // HEX
			'rgb(254,254,253)', // RGB
			'hsla(60,33%,99%,1)', // HSLA
		];

		yield [
			'#040504', // HEX
			'rgb(4,5,4)', // RGB
			'hsla(120,11%,2%,1)', // HSLA
		];

		yield [
			'#000000', // HEX
			'rgb(0,0,0)', // RGB
			'hsla(0,0%,0%,1)', // HSLA
		];

		yield [
			'#808080', // HEX
			'rgb(128,128,128)', // RGB
			'hsla(0,0%,50%,1)', // HSLA
		];

		yield [
			'#ffffff', // HEX
			'rgb(255,255,255)', // RGB
			'hsla(0,0%,100%,1)', // HSLA
		];
	}

	/**
	 * @dataProvider colorProvider
	 * @test
	 */
	public function itShouldConvertEdgeCase( $hex, $rgb, $hsla ) {
		$this->color = $hex;
		$sut = $this->getInstance();

		$this->assertStringMatchesFormat( $hex, (string) $sut->toHex(), '' );
		$this->assertStringMatchesFormat( $rgb, (string) $sut->toRgb(), '' );
		$this->assertStringMatchesFormat( $hsla, (string) $sut->toHsla(), '' );

		$this->assertStringMatchesFormat( $hex, (string) $sut->toRgb()->toHex(), '' );
		$this->assertStringMatchesFormat( $hsla, (string) $sut->toRgb()->toHsla(), '' );

		$this->assertStringMatchesFormat( $rgb, (string) $sut->toHex()->toRgb(), '' );
		$this->assertStringMatchesFormat( $hsla, (string) $sut->toHex()->toHsla(), '' );

//		$this->assertStringMatchesFormat( $hex, (string) $sut->toHsla()->toHex(), '' );
//		$this->assertStringMatchesFormat( $rgb, (string) $sut->toHsla()->toRgb(), '' );
	}
}
