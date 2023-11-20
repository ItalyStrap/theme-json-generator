<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Custom;

use ItalyStrap\ThemeJsonGenerator\Domain\Settings\CommonTrait;

class RawArray
{
    use CommonTrait;

    public const CATEGORY = 'custom';

    private string $key;
    private array $value;

    /**
     * @param array<int|string, mixed> $value
     */
    public function __construct(
        string $key,
        array $value
    ) {
        $this->key = $key;
        $this->value = $value;
    }

    public function slug(): string
    {
        return $this->key;
    }

    public function prop(): string
    {
        return \sprintf(
            '--wp--%s--%s',
            $this->camelToUnderscore($this->category()),
            $this->camelToUnderscore($this->slug())
        );
    }
}
