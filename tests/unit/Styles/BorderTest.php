<?php
declare(strict_types=1);

namespace ItalyStrap\Tests\Styles;

use Codeception\Test\Unit;
use ItalyStrap\ThemeJsonGenerator\Styles\Border;
use ItalyStrap\Tests\BaseUnitTrait;

class BorderTest extends Unit {

	use BaseUnitTrait, CommonTests;

	protected function getInstance(): Border {
		$sut = new Border();
		$this->assertInstanceOf( Border::class, $sut, '' );
		return $sut;
	}

	/**
	 * @test
	 */
	public function itShouldCreateCorrectArray() {
		$sut = $this->getInstance();
		$result = $sut
			->color('#000000')
			->style('solid')
			->width('1px')
			->radius('none')
			->toArray();

		$this->assertIsArray( $result, '' );
		$this->assertArrayHasKey( 'color', $result, '' );
		$this->assertArrayHasKey( 'style', $result, '' );
		$this->assertArrayHasKey( 'width', $result, '' );
		$this->assertArrayHasKey( 'radius', $result, '' );

		$this->assertStringMatchesFormat( '#000000', $result['color'], '' );
		$this->assertStringMatchesFormat( 'solid', $result['style'], '' );
		$this->assertStringMatchesFormat( '1px', $result['width'], '' );
		$this->assertStringMatchesFormat( 'none', $result['radius'], '' );
	}
}
