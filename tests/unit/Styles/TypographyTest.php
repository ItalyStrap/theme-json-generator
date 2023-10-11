<?php
declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Styles;

use ItalyStrap\Tests\Unit\BaseUnitTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Styles\Typography;

class TypographyTest extends UnitTestCase {

	use BaseUnitTrait;

	protected function makeInstance(): Typography {
		return new Typography();
	}

	/**
	 * @test
	 */
	public function itShouldCreateCorrectArray() {
		$sut = $this->makeInstance();
		$result = $sut
			->textDecoration( 'none' )
			->lineHeight( '1' )
			->fontSize( '25px' )
			->fontWeight( '800' )
			->textTransform( 'uppercase' )
			->fontStyle( 'value' )
			->letterSpacing( '1rem' )
			->fontFamily( 'serif' )
			->toArray();

		$this->assertIsArray( $result, '' );
		$this->assertArrayHasKey( 'textDecoration', $result, '' );
		$this->assertArrayHasKey( 'lineHeight', $result, '' );
		$this->assertArrayHasKey( 'fontSize', $result, '' );
		$this->assertArrayHasKey( 'fontWeight', $result, '' );
		$this->assertArrayHasKey( 'textTransform', $result, '' );
		$this->assertArrayHasKey( 'fontStyle', $result, '' );
		$this->assertArrayHasKey( 'letterSpacing', $result, '' );
		$this->assertArrayHasKey( 'fontFamily', $result, '' );

		$this->assertStringMatchesFormat( 'none', $result['textDecoration'], '' );
		$this->assertStringMatchesFormat( '1', $result['lineHeight'], '' );
		$this->assertStringMatchesFormat( '25px', $result['fontSize'], '' );
		$this->assertStringMatchesFormat( '800', $result['fontWeight'], '' );
		$this->assertStringMatchesFormat( 'uppercase', $result['textTransform'], '' );
		$this->assertStringMatchesFormat( 'value', $result['fontStyle'], '' );
		$this->assertStringMatchesFormat( '1rem', $result['letterSpacing'], '' );
		$this->assertStringMatchesFormat( 'serif', $result['fontFamily'], '' );
	}
}
