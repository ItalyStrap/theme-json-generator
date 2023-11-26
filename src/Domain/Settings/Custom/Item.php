<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Custom;

use ItalyStrap\ThemeJsonGenerator\Domain\Settings\ItemInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\CommonTrait;

/**
 * @psalm-api
 */
class Item implements ItemInterface
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
        $this->name = \ucfirst(\str_replace('.', ' ', $this->slug()));
        $this->value = $value;
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

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'name' => $this->name,
            'value' => $this->value,
        ];
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
