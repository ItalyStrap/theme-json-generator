<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings;

use ItalyStrap\Config\AccessValueInArrayWithNotationTrait;

class Collection
{
    use AccessValueInArrayWithNotationTrait;

    private array $collection = [];

    public function add($valueObject): self
    {
        $key = $valueObject::CATEGORY . '.' . $valueObject->slug();
        if ($this->get($key)) {
            throw new \RuntimeException("{$valueObject->slug()} already exists in $valueObject::CATEGORY");
        }

        $this->insertValue(
            $this->collection,
            (array)\explode('.', $key),
            $valueObject
        );

        return $this;
    }

    public function addMultiple(array $valueObjects): self
    {
        foreach ($valueObjects as $valueObject) {
            $this->add($valueObject);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->findValue($this->collection, \explode('.', $key), $default);
    }

    public function has(string $key): bool
    {
        return (bool)$this->get($key);
    }

    public function toArrayByCategory(string $category): array
    {
        if ($category === 'custom') {
            return $this->processCustomCollection($this->get($category, []));
        }

        return \array_values(
            \array_map(
                fn($item) => $item->toArray(),
                $this->get($category, [])
            )
        );
    }

    private function processCustomCollection(array $collection, string $prefix = ''): array
    {
        $processed = [];
        foreach ($collection as $key => $value) {
            $fullKey = $prefix === '' ? $key : $prefix . '.' . $key;
            if (\is_array($value)) {
                $processed[$key] = $this->processCustomCollection($value, $fullKey);
                continue;
            }

            $processed[$key] = (string)$value;
        }

        return $processed;
    }
}
