<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Utilities;

class ClampExperimental
{
    private $value;
    private $min;
    private $max;

    public function __construct(
        $value,
        $min,
        $max
    ) {
        $this->value = $value;
        $this->min = $min;
        $this->max = $max;
    }
}
