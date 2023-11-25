<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Utilities;

/**
 * @psalm-api
 */
class SupportedUnitsExperimental implements UnitInterfaceExperimental
{
    private array $units;

    /**
     * @param string[] $units
     */
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
