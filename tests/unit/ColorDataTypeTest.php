<?php
declare(strict_types=1);

namespace ItalyStrap\Tests;

use Codeception\Test\Unit;
use ItalyStrap\ThemeJsonGenerator\ColorDataType;

class ColorDataTypeTest extends Unit {

	use BaseUnitTrait;

	protected string $color = '';


	// phpcs:ignore
	protected function _before() {
		$this->color = '#000000';
	}

	protected function getInstance(): ColorDataType {
		$sut = new ColorDataType( $this->color );
		return $sut;
	}

	public function colorProvider() {
		yield 'Dark'	=> [
			'#000000'
		];
	}

	/**
	 * @dataProvider colorProvider
	 * @test
	 */
//	public function itShouldBe() {
//		$this->color = '#000000';
//		$sut = $this->getInstance();
//
//		$this->assertTrue( $sut->isDark() );
//	}

	/**
	 * @test
	 */
	public function itShouldBeDark() {
		$this->color = '#000000';
		$sut = $this->getInstance();

		$this->assertTrue( $sut->isDark() );
	}

	/**
	 * @test
	 */
	public function itShouldNotBeDark() {
		$this->color = '#ffffff';
		$sut = $this->getInstance();

		$this->assertFalse( $sut->isDark() );
	}

	/**
	 * @test
	 */
	public function itShouldBeLight() {
		$this->color = '#ffffff';
		$sut = $this->getInstance();

		$this->assertTrue( $sut->isLight() );
	}

	/**
	 * @test
	 */
	public function itShouldNotBeLight() {
		$this->color = '#000000';
		$sut = $this->getInstance();

		$this->assertFalse( $sut->isLight() );
	}

	/**
	 * @test
	 */
	public function itShouldReturnHex() {
		$this->color = '#000000';
		$sut = $this->getInstance();

		$this->assertStringMatchesFormat( $this->color, $sut->toHex(), '' );
	}

	/**
	 * @test
	 */
	public function itShouldReturnRgb() {
		$this->color = '#000000';
		$sut = $this->getInstance();

		$this->assertStringMatchesFormat('rgb(0,0,0)', $sut->toRgb(), '' );
	}

	/**
	 * @test
	 */
	public function itShouldReturnRgba() {
		$this->color = '#000000';
		$sut = $this->getInstance();

		$this->assertStringMatchesFormat('rgba(0,0,0,1.00)', $sut->toRgba(), '' );
	}

	/**
	 * @test
	 */
	public function itShouldReturnHsl() {
		$this->color = '#000000';
		$sut = $this->getInstance();

		$this->assertStringMatchesFormat('hsl(0,0%,0%)', $sut->toHsl(), '' );
	}

	/**
	 * @test
	 */
	public function itShouldReturnHsla() {
		$this->color = '#000000';
		$sut = $this->getInstance();

		$this->assertStringMatchesFormat('hsla(0,0%,0%,1)', $sut->toHsla(), '' );
	}

	/**
	 * @test
	 */
	public function itShouldReturnDarkenColor() {
		$this->color = '#ffffff';
		$sut = $this->getInstance();

		$this->assertStringMatchesFormat('#cccccc', $sut->darken( 20 )->toHex(), '' );
	}

	/**
	 * @test
	 */
	public function itShouldReturnLightenColor() {
		$this->color = '#000000';
		$sut = $this->getInstance();

		$this->assertStringMatchesFormat('#333333', $sut->lighten( 20 )->toHex(), '' );
	}

	/**
	 * @test
	 */
	public function itShouldReturnMixedColor() {
		$this->color = '#000000';
		$sut = $this->getInstance();

		$this->assertStringMatchesFormat('#7f7f7f', $sut->mix( '#ffffff' )->toHex(), '' );
	}

	/**
	 * @test
	 */
	public function itShouldReturnComplementaryColor() {
		$this->color = '#ff0000';
		$sut = $this->getInstance();

		$this->assertStringMatchesFormat('#00ffff', $sut->complementary()->toHex(), '' );
	}
}
