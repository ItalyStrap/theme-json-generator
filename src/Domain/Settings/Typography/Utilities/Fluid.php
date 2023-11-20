<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Typography\Utilities;

/**
 * @psalm-api
 */
class Fluid
{
    public const KEY = 'fluid';

    public const MIN = 'min';
    public const MAX = 'max';

    private string $min;
    private string $max;

    public function __construct(string $min, string $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function toArray(): array
    {
        return [
            self::MIN => $this->min,
            self::MAX => $this->max,
        ];
    }
}
