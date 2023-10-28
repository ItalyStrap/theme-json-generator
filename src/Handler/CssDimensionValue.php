<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Handler;

final class CssDimensionValue
{
    private float $value;
    private string $unit;

    public function __construct(float $value, string $unit, UnitInterface $supportedUnits)
    {
        if (!$supportedUnits->isValid($unit)) {
            throw new \InvalidArgumentException("Unit '{$unit}' is not supported.");
        }

        $this->value = $value;
        $this->unit = $unit;
    }

    public function value(): float
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
