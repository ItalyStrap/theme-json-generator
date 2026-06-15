<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Custom;

use ItalyStrap\ThemeJsonGenerator\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetTrait;

final readonly class Custom implements PresetInterface
{
    use PresetTrait;

    /**
     * @var string
     */
    public const TYPE = 'custom';

    private string $name;

    public function __construct(
        private string $key,
        private string $value
    ) {
        $this->name = \ucfirst(\str_replace('.', ' ', $this->key));
    }

    public function slug(): string
    {
        return $this->key;
    }

    public function prop(): string
    {
        return \sprintf(
            '--wp--%s--%s',
            $this->type(),
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
