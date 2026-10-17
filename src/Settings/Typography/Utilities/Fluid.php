<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Typography\Utilities;

/**
 * @todo Evaluate if it makes sense to check that min and max are valid CSS units, and that min is less than max
 */
final readonly class Fluid implements \JsonSerializable
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
        private string $min,
        private string $max
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

    /**
     * @return array{min: string, max: string}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
