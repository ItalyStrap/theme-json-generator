<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Utilities;

/**
 * @psalm-api
 */
class CalcExperimental
{
    private string $value;

    public function __construct(string ...$value)
    {
        $this->value = \sprintf(
            'calc(%s)',
            \implode(' ', $value)
        );
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
