<?php
declare(strict_types=1);

namespace ItalyStrap\Tests\Styles;

trait CommonTests {

	/**
	 * @test
	 */
	public function itShouldCreateUserDefinedProperty() {
		$sut = $this->getInstance();
		$result = $sut->property( 'style', '#000000' )->toArray();

		$this->assertStringMatchesFormat( '#000000', $result['style'], '' );
	}

	/**
	 * @test
	 */
	public function itShouldBeImmutable() {
		$sut = $this->getInstance();
		$sut->property( 'style', '#000000' );

		$this->expectException( \RuntimeException::class );
		$sut->property( 'style', '#000000' );
	}

	/**
	 * @test
	 */
	public function itShouldBeImmutableAlsoIfICloneIt() {
		$sut = $this->getInstance();
		$sut->property( 'style', '#000000' );

		$sut_cloned = clone $sut;

		$this->assertNotEmpty( $sut->toArray(), '' );
		$this->assertEmpty( $sut_cloned->toArray(), '' );

		$sut_cloned->property( 'style', '#000000' );
		$sut_cloned->property( 'new-style', '#000000' );


		$this->assertNotSame( $sut->toArray(), $sut_cloned->toArray(), '' );
	}

	/**
	 *
	 */
//	public function itShouldBeStringable() {
//
//		$sut = $this->getInstance();
//		$sut->property( 'style', '#000000' );
//
//		$this->assertStringMatchesFormat( '#000000', (string) $sut, '' );
//	}
}
