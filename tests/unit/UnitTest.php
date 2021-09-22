<?php
declare(strict_types=1);

namespace ItalyStrap\Tests\Unit;

use Codeception\Test\Unit;

abstract class UnitTest extends Unit {

	/**
	 * @var \UnitTester
	 */
	protected $tester;

	// phpcs:ignore
	protected function _before() {
	}

	// phpcs:ignore
	protected function _after() {
	}
}
