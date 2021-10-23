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
}
