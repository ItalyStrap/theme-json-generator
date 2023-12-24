<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Custom;

/**
 * @psalm-api
 */
class CollectionAdapter
{
    /**
     * @var array<string, mixed>
     */
    private array $collection = [];

    /**
     * @param array<string, mixed> $collection
     */
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

        /**
         * @var string|array|\Stringable $value
         */
        foreach ($collection as $key => $value) {
            $fullKey = (string)($prefix === '' ? $key : $prefix . '.' . $key);
            if (\is_array($value)) {
                $processed = \array_merge($processed, $this->collectionToFlat($value, $fullKey));
                continue;
            }

            $processed[] = new Custom($fullKey, (string)$value);
        }

        return $processed;
    }
}
