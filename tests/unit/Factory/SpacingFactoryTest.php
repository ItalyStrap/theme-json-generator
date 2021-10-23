<?php
declare(strict_types=1);

namespace ItalyStrap\Tests;

use Codeception\Test\Unit;
use ItalyStrap\ThemeJsonGenerator\Factory\Spacing;

class SpacingFactoryTest extends Unit {

	use BaseUnitTrait;

	public function valueProvider(): \Generator {

		yield '1 value'	=> [
			['0px'],
			[
				'top'		=> '0px',
				'right'		=> '0px',
				'bottom'	=> '0px',
				'left'		=> '0px',
			]
		];

		yield '2 values'	=> [
			['0px', 'auto'],
			[
				'top'		=> '0px',
				'right'		=> 'auto',
				'bottom'	=> '0px',
				'left'		=> 'auto',
			]
		];

		yield '3 values'	=> [
			['0px', 'auto', '10px'],
			[
				'top'		=> '0px',
				'right'		=> 'auto',
				'bottom'	=> '10px',
				'left'		=> 'auto',
			]
		];

		yield '4 values'	=> [
			['1px', '2px', '3px', '4px'],
			[
				'top'		=> '1px',
				'right'		=> '2px',
				'bottom'	=> '3px',
				'left'		=> '4px',
			]
		];

		yield '4 values with some empty'	=> [
			['1px', '', '3px', ''],
			[
				'top'		=> '1px',
				'bottom'	=> '3px',
			]
		];
	}

	/**
	 * @dataProvider valueProvider
	 * @test
	 */
	public function itShouldReturnArrayWithSpacingPropsFor( array $value, array $expected ) {
		$sut = Spacing::shorthand( $value );
		$this->assertEquals($expected, $sut->toArray(), '');
	}

	protected function getInstance() {
		// TODO: Implement getInstance() method.
	}
}
