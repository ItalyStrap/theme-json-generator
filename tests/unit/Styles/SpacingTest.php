<?php
declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Styles;


use ItalyStrap\Tests\Unit\BaseUnitTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Styles\Spacing;

class SpacingTest extends UnitTestCase {

	use BaseUnitTrait, CommonTests;

	protected function makeInstance(): Spacing {
		return new Spacing();
	}

	public function methodsProvider(): \Generator {
		yield 'Top' => [
			'top',
			'25px'
		];

		yield 'Right' => [
			'right',
			'25px'
		];

		yield 'Bottom' => [
			'bottom',
			'25px'
		];

		yield 'Left' => [
			'left',
			'25px'
		];
	}

	/**
	 * @dataProvider methodsProvider
	 * @test
	 */
	public function itShouldReturnAnArray( string $method, string $value ) {

		$sut = $this->makeInstance();
//		$sut->top('25px')
//			->right('25px')
//			->bottom( '25px' )
//			->left('25px');

//		codecept_debug( \get_class_methods( $sut ) );

		\call_user_func( [ $sut, $method ], $value );

		$this->assertIsArray( $sut->toArray(), '' );
	}

	/**
	 * @test
	 */
	public function itShouldReturnTheCorrectValue() {

		$sut = $this->makeInstance();
		$sut->top('25px')
			->right('25px')
			->bottom( '50px' )
			->left( '5rem' );

		$this->assertEquals(
			[
				'top'		=> '25px',
				'right'		=> '25px',
				'bottom'	=> '50px',
				'left'		=> '5rem',
			],
			$sut->toArray()
		);
	}

	/**
	 * @test
	 */
	public function itShouldBeImmutable() {

		$sut = $this->makeInstance();
		$sut->top('25px')
			->left('25px');

		$this->expectException( \RuntimeException::class );
		$sut->top( '22' );
	}

	/**
	 * @test
	 */
	public function itShouldBeImmutableAlsoIfICloneIt() {

		$sut = $this->makeInstance();
		$sut->top('25px')
			->left('25px');

		$sut_cloned = clone $sut;

		$this->assertNotEmpty( $sut->toArray(), '' );
		$this->assertEmpty( $sut_cloned->toArray(), '' );

		$sut_cloned->left('20px');
	}

	/**
	 * @test
	 */
	public function itShouldBeStringable() {

		$sut = $this->makeInstance();

		$sut->top('25px');
		$this->assertStringMatchesFormat( '25px 0 0 0', (string) $sut, '' );

		$sut->bottom('20px');
		$this->assertStringMatchesFormat( '25px 0 20px 0', (string) $sut, '' );

		$sut->left('0');
		$this->assertStringMatchesFormat( '25px 0 20px 0', (string) $sut, '' );

		$sut->right('50px');
		$this->assertStringMatchesFormat( '25px 50px 20px 0', (string) $sut, '' );
	}

	public function sameValueProvider() {

		yield 'Value of 0'	=> [
			['0','0','0','0',],
			'0'
		];

		yield 'Value of 0px'	=> [
			['0px','0px','0px','0px',],
			'0px'
		];

		yield 'Value of 0 if we have 0px and 0'	=> [
			['0px','0','0px','0px',],
			'0px'
		];

		yield 'Value of 42px'	=> [
			['42px','42px','42px','42px',],
			'42px'
		];

		yield 'Value of 42px even if we have 42 with no unit'	=> [
			['42px','42','42px','42px',],
			'42px'
		];
	}

	/**
	 * @dataProvider sameValueProvider
	 * @test
	 */
	public function itShouldBeStringableAndReturnOnly( array $value, string $expected ) {

		$sut = $this->makeInstance();

		$sut->top( $value[0] );
		$sut->bottom( $value[1] );
		$sut->left( $value[2] );
		$sut->right( $value[3] );

		$this->assertStringMatchesFormat( $expected, (string) $sut, '' );
	}
}
