<?php
declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Styles;

use ItalyStrap\Tests\Unit\BaseUnitTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Styles\Color;

class ColorTest extends UnitTestCase {

	use BaseUnitTrait;

	protected function makeInstance(): Color {
		$sut = new Color();
		$this->assertInstanceOf( Color::class, $sut, '' );
		return $sut;
	}

	/**
	 * @test
	 */
	public function itShouldCreateCorrectArray() {
		$sut = $this->makeInstance();
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
