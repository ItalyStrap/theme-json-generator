<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Handler;

class SupportedUnits implements UnitInterface
{
    private array $units;

    public function __construct(array $units)
    {
        foreach ($units as $unit) {
            if (!in_array($unit, self::VALID_UNITS)) {
                throw new \InvalidArgumentException("Unit '{$unit}' is not a valid CSS unit.");
            }
        }

        $this->units = $units;
    }

    public function isValid(string $unit): bool
    {
        return in_array($unit, $this->units);
    }

    public function units(): array
    {
        return $this->units;
    }
}
