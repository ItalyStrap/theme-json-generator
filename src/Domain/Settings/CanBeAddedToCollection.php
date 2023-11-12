<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings;

interface CanBeAddedToCollection
{
	public function toArray(): array;
}
