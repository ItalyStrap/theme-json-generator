<?php
declare(strict_types=1);

namespace ItalyStrap\Tests;

use Codeception\Test\Unit;
use ItalyStrap\ThemeJsonGenerator\ColorDataType;

class ColorDataTypeTest extends Unit {

	use BaseUnitTrait;

	protected string $base_color = '';


	// phpcs:ignore
	protected function _before() {
		$this->base_color = '#000000';
	}

	protected function getInstance(): ColorDataType {
		$sut = new ColorDataType( $this->base_color );
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
		$this->base_color = '#000000';
		$sut = $this->getInstance();

		$this->assertTrue( $sut->isDark() );
	}

	/**
	 * @test
	 */
	public function itShouldNotBeDark() {
		$this->base_color = '#ffffff';
		$sut = $this->getInstance();

		$this->assertFalse( $sut->isDark() );
	}

	/**
	 * @test
	 */
	public function itShouldBeLight() {
		$this->base_color = '#ffffff';
		$sut = $this->getInstance();

		$this->assertTrue( $sut->isLight() );
	}

	/**
	 * @test
	 */
	public function itShouldNotBeLight() {
		$this->base_color = '#000000';
		$sut = $this->getInstance();

		$this->assertFalse( $sut->isLight() );
	}

	/**
	 * @test
	 */
	public function itShouldReturnHex() {
		$this->base_color = '#000000';
		$sut = $this->getInstance();

		$this->assertStringMatchesFormat( $this->base_color, $sut->toHex(), '' );
	}

	/**
	 * @test
	 */
	public function itShouldReturnRgb() {
		$this->base_color = '#000000';
		$sut = $this->getInstance();

		$this->assertStringMatchesFormat('rgb(0,0,0)', $sut->toRgb(), '' );
	}

	/**
	 * @test
	 */
	public function itShouldReturnRgba() {
		$this->base_color = '#000000';
		$sut = $this->getInstance();

		$this->assertStringMatchesFormat('rgba(0,0,0,1.00)', $sut->toRgba(), '' );
	}

	/**
	 * @test
	 */
	public function itShouldReturnHsl() {
		$this->base_color = '#000000';
		$sut = $this->getInstance();

		$this->assertStringMatchesFormat('hsl(0,0%,0%)', $sut->toHsl(), '' );
	}

	/**
	 * @test
	 */
	public function itShouldReturnHsla() {
		$this->base_color = '#000000';
		$sut = $this->getInstance();

		$this->assertStringMatchesFormat('hsla(0,0%,0%,1)', $sut->toHsla(), '' );
	}

	/**
	 * @test
	 */
	public function itShouldReturnDarkenColor() {
		$this->base_color = '#ffffff';
		$sut = $this->getInstance();

		$this->assertStringMatchesFormat('#cccccc', $sut->darken( 20 )->toHex(), '' );
	}

	/**
	 * @test
	 */
	public function itShouldReturnLightenColor() {
		$this->base_color = '#000000';
		$sut = $this->getInstance();

		$this->assertStringMatchesFormat('#333333', $sut->lighten( 20 )->toHex(), '' );
	}

	public function weightProvider() {

		yield '1 white' => [
			// weight
			1,
			// Expected
			'#ffffff',
			// Mixed with
			'#ffffff'
		];

		yield '0.8 white' => [
			// weight
			0.8,
			// Expected
			'#e5e5e5',
			// Mixed with
			'#ffffff'
		];

		yield '0.6 white' => [
			// weight
			0.6,
			// Expected
			'#cccccc',
			// Mixed with
			'#ffffff'
		];

		yield '0.4 white' => [
			// weight
			0.4,
			// Expected
			'#b2b2b2',
			// Mixed with
			'#ffffff'
		];

		yield '0.2 white' => [
			// weight
			0.2,
			// Expected
			'#999999',
			// Mixed with
			'#ffffff'
		];

		yield '0 white' => [
			// weight
			0,
			// Expected
			'#7f7f7f',
			// Mixed with
			'#ffffff'
		];

		yield '1 black' => [
			// weight
			1,
			// Expected
			'#000000',
			// Mixed with
			'#000000'
		];

		yield '0.8 black' => [
			// weight
			0.8,
			// Expected
			'#191919',
			// Mixed with
			'#000000'
		];

		yield '0.6 black' => [
			// weight
			0.6,
			// Expected
			'#333333',
			// Mixed with
			'#000000'
		];

		yield '0.4 black' => [
			// weight
			0.4,
			// Expected
			'#4c4c4c',
			// Mixed with
			'#000000'
		];

		yield '0.2 black' => [
			// weight
			0.2,
			// Expected
			'#666666',
			// Mixed with
			'#000000'
		];

		yield '0 black' => [
			// weight
			0,
			// Expected
			'#7f7f7f',
			// Mixed with
			'#000000'
		];
	}

	/**
	 * @dataProvider weightProvider
	 * @test
	 */
	public function itShouldReturnMixedColorByWeight( float $weight, string $expected, string $mixedWith ) {

		$this->base_color = '#7f7f7f';
		$sut = $this->getInstance();

		$this->assertStringMatchesFormat( $expected, $sut->mix( $mixedWith, $weight  )->toHex(), '' );
	}

	/**
	 * @test
	 */
	public function itShouldTintAndShade() {

		$this->base_color = '#7f7f7f';
		$sut = $this->getInstance();

		$this->assertStringMatchesFormat( '#cccccc', $sut->tint( 0.6 )->toHex(), '' );
		$this->assertStringMatchesFormat( '#333333', $sut->shade( 0.6 )->toHex(), '' );
	}

	/**
	 * @test
	 */
	public function itShouldReturnComplementaryColor() {
		$this->base_color = '#ff0000';
		$sut = $this->getInstance();

		$this->assertStringMatchesFormat('#00ffff', $sut->complementary()->toHex(), '' );
	}
}
