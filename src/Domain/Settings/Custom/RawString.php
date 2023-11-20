<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Custom;

use ItalyStrap\ThemeJsonGenerator\Domain\Settings\CommonTrait;

class RawString
{
    use CommonTrait;

    public const CATEGORY = 'custom';

    private string $key;
    private string $value;
    private string $name;

    public function __construct(
        string $key,
        string $value
    ) {
        $this->key = $key;
        $this->value = $value;
        $this->name = \ucfirst(\str_replace('.', ' ', $this->slug()));
    }

    public function slug(): string
    {
        return $this->key;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function prop(): string
    {
        return \sprintf(
            '--wp--%s--%s',
            $this->category(),
            $this->camelToUnderscore(\str_replace('.', '--', $this->slug()))
        );
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
