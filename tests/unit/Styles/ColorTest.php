<?php
declare(strict_types=1);

namespace ItalyStrap\Tests\Styles;

use ItalyStrap\ThemeJsonGenerator\Styles\Color;
use ItalyStrap\Tests\BaseUnitTrait;

class ColorTest extends \Codeception\Test\Unit {

	use BaseUnitTrait;

	protected function getInstance(): Color {
		$sut = new Color();
		$this->assertInstanceOf( Color::class, $sut, '' );
		return $sut;
	}

	/**
	 * @test
	 */
	public function itShouldCreateCorrectArray() {
		$sut = $this->getInstance();
		$result = $sut
			->text( '#000000' )
			->background( 'transparent' )
			->gradient( 'value' )
			->toArray();

		$this->assertIsArray( $result, '' );
		$this->assertArrayHasKey( 'text', $result, '' );
		$this->assertArrayHasKey( 'background', $result, '' );
		$this->assertArrayHasKey( 'gradient', $result, '' );

		$this->assertStringMatchesFormat( '#000000', $result['text'], '' );
		$this->assertStringMatchesFormat( 'transparent', $result['background'], '' );
		$this->assertStringMatchesFormat( 'value', $result['gradient'], '' );
	}
}
