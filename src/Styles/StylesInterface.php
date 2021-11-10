<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

interface StylesInterface {
	public function property( string $property, string $value ): self;
}
