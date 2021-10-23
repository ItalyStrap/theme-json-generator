<?php
declare(strict_types=1);

namespace ItalyStrap\Tests\Styles;

use Codeception\Test\Unit;
use ItalyStrap\ThemeJsonGenerator\Styles\Typography;
use ItalyStrap\Tests\BaseUnitTrait;

class TypographyTest extends Unit {

	use BaseUnitTrait;

	protected function getInstance(): Typography {
		$sut = new Typography();
		$this->assertInstanceOf( Typography::class, $sut, '' );
		return $sut;
	}
}
