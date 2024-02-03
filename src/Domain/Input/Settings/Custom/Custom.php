<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Custom;

use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetTrait;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetInterface;

/**
 * @psalm-api
 */
class Custom implements PresetInterface
{
    use PresetTrait;

    /**
     * @var string
     */
    public const CATEGORY = 'custom';

    private string $key;

    private string $value;

    private string $name;

    public function __construct(
        string $key,
        string $value
    ) {
        $this->key = $key;
        $this->name = \ucfirst(\str_replace('.', ' ', $this->key));
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
            $this->category(),
            $this->camelToSnake(\str_replace('.', '--', $this->key))
        );
    }

    /**
     * @return array{key: string, name: string, value: string}
     */
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
