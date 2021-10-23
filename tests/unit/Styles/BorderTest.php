<?php
declare(strict_types=1);

namespace ItalyStrap\Tests\Styles;

use Codeception\Test\Unit;
use ItalyStrap\ThemeJsonGenerator\Styles\Border;
use ItalyStrap\Tests\BaseUnitTrait;

class BorderTest extends Unit {

	use BaseUnitTrait;

	protected function getInstance(): Border {
		$sut = new Border();
		$this->assertInstanceOf( Border::class, $sut, '' );
		return $sut;
	}
}
