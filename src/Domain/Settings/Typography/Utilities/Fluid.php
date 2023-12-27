<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Typography\Utilities;

/**
 * @psalm-api
 */
class Fluid
{
    /**
     * @var string
     */
    public const KEY = 'fluid';

    /**
     * @var string
     */
    public const MIN = 'min';

    /**
     * @var string
     */
    public const MAX = 'max';

    private string $min;

    private string $max;

    public function __construct(string $min, string $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * @return array{min: string, max: string}
     */
    public function toArray(): array
    {
        return [
            self::MIN => $this->min,
            self::MAX => $this->max,
        ];
    }
}
