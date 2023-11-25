<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Custom;

/**
 * @psalm-api
 */
class CollectionAdapter
{
    private array $collection;

    public function __construct(
        array $collection = []
    ) {
        $this->collection = $collection;
    }

    public function toArray(): array
    {
        return $this->collectionToFlat($this->collection);
    }

    private function collectionToFlat(array $collection, string $prefix = ''): array
    {
        $processed = [];
        foreach ($collection as $key => $value) {
            $fullKey = $prefix === '' ? $key : $prefix . '.' . $key;
            if (\is_array($value)) {
                $processed = \array_merge($processed, $this->collectionToFlat($value, $fullKey));
                continue;
            }

            $processed[] = new Item($fullKey, (string)$value);
        }

        return $processed;
    }
}
