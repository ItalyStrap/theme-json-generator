<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Utilities;

/**
 * @psalm-api
 */
final class DimensionExperimental
{
    private string $value;
    private string $unit;

    public function __construct(string $value, string $unit = '')
    {
         \preg_match_all('/([0-9]+)([a-z%]+)/', $value, $matches);

         $value = $matches[1][0] ?? '';
         $unit = $matches[2][0] ?? '';

        if (!\in_array($unit, ['px', 'rem', 'em', '%'], true)) {
            throw new \InvalidArgumentException("Unit '{$unit}' is not supported.");
        }

        $this->value = $value;
        $this->unit = $unit;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function unit(): string
    {
        return $this->unit;
    }

    public function __toString(): string
    {
        return $this->value . $this->unit;
    }
}