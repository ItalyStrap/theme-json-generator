<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Handler;

interface ColorAdjustInterface {

	public function tint( float $weight = 0 ): ColorInfoInterface;

	public function shade( float $weight = 0 ): ColorInfoInterface;

	public function tone( float $weight = 0 ): ColorInfoInterface;

	public function opacity( float $alpha = 1 ): ColorInfoInterface;

	public function darken( int $amount = 0 ): ColorInfoInterface;

	public function lighten( int $amount = 0 ): ColorInfoInterface;
}
