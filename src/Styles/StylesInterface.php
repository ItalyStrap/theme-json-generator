<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

/**
 * @psalm-api
 */
interface StylesInterface
{
    public function property(string $property, string $value): self;
}
