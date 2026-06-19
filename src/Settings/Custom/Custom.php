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

    private string $key;

    private string $name;

    private string $value;

    /**
     * @param list<string>|string $key
     */
    public function __construct(
        array|string $key,
        mixed $value
    ) {
        $this->key = $this->normalizeKey($key);
        $this->value = $this->validateValue($value);
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

    /**
     * @param list<string>|string $key
     */
    private function normalizeKey(array|string $key): string
    {
        $segments = \is_string($key) ? [$key] : $key;
        $formattedKey = $segments === [] ? '<empty>' : \implode('.', $segments);

        foreach ($segments as $segment) {
            if ($segment !== '') {
                continue;
            }

            throw new \InvalidArgumentException(\sprintf(
                'Custom key "%s" contains an empty segment.',
                $formattedKey
            ));
        }

        if ($segments === []) {
            throw new \InvalidArgumentException('Custom key "<empty>" contains an empty segment.');
        }

        return $formattedKey;
    }

    private function validateValue(mixed $value): string
    {
        if (\is_string($value) && $value !== '') {
            return $value;
        }

        throw new \InvalidArgumentException(\sprintf(
            'Custom value for key "%s" must be a non-empty string.',
            $this->key
        ));
    }
}
