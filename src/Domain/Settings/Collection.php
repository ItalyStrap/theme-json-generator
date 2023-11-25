<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings;

use ItalyStrap\Config\AccessValueInArrayWithNotationTrait;

/**
 * @psalm-api
 */
class Collection implements CollectionInterface
{
    use AccessValueInArrayWithNotationTrait;

    private array $collection = [];

    public function add(ItemInterface $item): self
    {
        $key = $item->category() . '.' . $item->slug();
        $this->assertIsUnique($key, $item);

        $this->insertValue(
            $this->collection,
            \explode('.', $key),
            $item
        );

        return $this;
    }

    /**
     * @param ItemInterface[] $items
     */
    public function addMultiple(array $items): self
    {
        foreach ($items as $item) {
            $this->add($item);
        }

        return $this;
    }

    /**
     * @param mixed $default
     * @return mixed|string|null|ItemInterface
     */
    public function get(string $key, $default = null)
    {
        return $this->findValue($this->collection, \explode('.', $key), $default);
    }

    /**
     * @todo Filter empty values from collection
     */
    public function toArrayByCategory(string $category): array
    {
        if ($category === 'custom') {
            return $this->processCustomCollection((array)$this->get($category, []), 'custom');
        }

        return $this->processPresetCollection($category);
    }

    private function processPresetCollection(string $category): array
    {
        return \array_values(
            \array_map(
                function (ItemInterface $item) {
                    $newItems = [];
                    foreach ($item->toArray() as $key => $value) {
                        if (\is_string($value)) {
                            $value = $this->extractPlaceholders($value, '');
                        }
                        $newItems[$key] = $value;
                    }
                    return $newItems;
                },
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

            $processed[$key] = $this->extractPlaceholders((string)$value, $fullKey);
        }

        return $processed;
    }

    private function extractPlaceholders(string $string, string $key): string
    {
        $pattern = '/{{([\w.]+)}}|var:(preset|custom)\|([\w.]+)\|([\w.]+)/';
        preg_match_all($pattern, $string, $matches, PREG_SET_ORDER);

        $found = [];
        foreach ($matches as $match) {
            \array_shift($match);

            if (\strpos($match[0], '.') !== false) {
                $match = \explode('.', $match[0]);
            }

            $match = \array_filter($match);

            $found = $match;
        }

        if (\count($found) < 1) {
            return $string;
        }

        $newKey = \implode('.', $found);
        /**
         * @todo Add test for the second parameter of get()
         *       I don't remember why I put 'custom.' as prefix.
         *       This function is used also for the preset collection
         */
        $item = $this->get($newKey, $this->get('custom.' . $newKey));

        if ($item === null) {
            throw new \RuntimeException("{{{$newKey}}} does not exists");
        }

        return $item->var();
    }

    private function assertIsUnique(string $key, ItemInterface $item): void
    {
        if ($this->get($key)) {
            throw new \RuntimeException(
                \sprintf(
                    '%s already registered in %s category: got %s',
                    $item->slug(),
                    $item->category(),
                    $key
                )
            );
        }
    }
}
