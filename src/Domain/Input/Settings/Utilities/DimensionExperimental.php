<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Utilities;

/**
 * https://github.com/marfurt/measurements
 * https://github.com/pimlie/php-unit-conversion
 * https://github.com/PhpUnitsOfMeasure/php-units-of-measure
 * https://wiki.php.net/rfc/clamp
 * @psalm-api
 */
final class DimensionExperimental
{
    private string $value;

    private string $unit;

    public function __construct(string $value, string $unit = '')
    {
        \preg_match_all('#(\d+)([a-z%]+)#', $value, $matches);

        $value = $matches[1][0] ?? '';
        $unit = $matches[2][0] ?? '';

        if (!\in_array($unit, ['px', 'rem', 'em', '%'], true)) {
            throw new \InvalidArgumentException(sprintf("Unit '%s' is not supported.", $unit));
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
