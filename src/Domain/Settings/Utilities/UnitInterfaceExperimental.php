<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Utilities;

/**
 * @psalm-api
 */
interface UnitInterfaceExperimental
{
    /**
     * @var string[]
     */
    public const VALID_UNITS = ['px', 'em', 'rem', '%', 'vw', 'vh', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'ch'];

    public function isValid(string $unit): bool;

    public function units(): array;
}
