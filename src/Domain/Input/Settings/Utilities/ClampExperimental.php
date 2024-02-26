<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Utilities;

/**
 * @psalm-api
 */
class ClampExperimental
{
    private string $value;

    private string $min;

    private string $max;

    public function __construct(
        string $value,
        string $min,
        string $max
    ) {
        $this->value = $value;
        $this->min = $min;
        $this->max = $max;
    }

    public function __toString(): string
    {
        return \sprintf(
            'clamp(%s, %s, %s)',
            $this->value,
            $this->min,
            $this->max
        );
    }
}
