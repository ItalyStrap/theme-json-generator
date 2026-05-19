<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Utilities;

final readonly class ClampExperimental implements \Stringable
{
    public function __construct(private string $value, private string $min, private string $max)
    {
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
