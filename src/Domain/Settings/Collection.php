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
     */
    public function get(string $key, $default = null)
    {
        return $this->findValue($this->collection, \explode('.', $key), $default);
    }

    /**
     * This method is meant to be used only in the Styles Section or the Theme.json file
     * that because inside the styles section you only need to get a css variable created in the
     * Settings section.
     *
     * Now obviously if you need to pass other CSS accepted values you can also use them
     * and because they are not keys of the collection they will be returned as is.
     *
     * In short:
     *
     * If you pass a key of the collection you will get the value of the item.
     * Example:
     * Collection::value('color.base') === 'var(--wp--preset--color--base)'
     *
     * If you pass a CSS value you will get the same value (because all CSS value are not keys of the collection)
     * Example:
     * Collection::value('nonExistentKey', 'inherit') === 'inherit'
     *
     * @param string $key
     * @param mixed $default
     * @return string
     */
    public function value(string $key, $default = null): string
    {
        /**
         * @var ItemInterface|mixed $value
         */
        $value = $this->get($key, $default);

        if ($value instanceof ItemInterface) {
            $value = $value->var();
        }

        /**
         * This prevents to return a string with the placeholder
         */
        return $this->extractPlaceholders((string)$value, $key);
    }

    /**
     * @todo Filter empty values from collection
     */
    public function toArrayByCategory(string $category): array
    {
        if ($category === 'custom') {
            return $this->processCustomCollection((array)$this->get($category, []), 'custom');
        }

        return $this->processPresetCollection((array)$this->get($category, []));
    }

    private function processPresetCollection(array $collection): array
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
                $collection
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
        $shiftedItem = '';
        foreach ($matches as $match) {
            $shiftedItem = \array_shift($match);

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

        return \str_replace($shiftedItem, $item->var(), $string);
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
