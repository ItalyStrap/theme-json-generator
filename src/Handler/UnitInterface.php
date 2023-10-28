<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Handler;

interface UnitInterface
{
	const VALID_UNITS = ['px', 'em', 'rem', '%', 'vw', 'vh', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'ch'];

	public function isValid(string $unit): bool;

	public function units(): array;
}
