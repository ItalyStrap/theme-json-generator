<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Typography\Utilities;

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

    public function __construct(
        private readonly string $min,
        private readonly string $max
    ) {
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
