<?php
declare(strict_types=1);

namespace ItalyStrap\Tests\Unit;

trait BaseUnitTrait {

	abstract protected function makeInstance();

	/**
	 * @test
	 */
	public function itShouldBeInstantiable() {
		$sut = $this->makeInstance();
//		if ( ! $sut ) {
//			$this->fail( 'Create an instance' );
//		}
	}
}
