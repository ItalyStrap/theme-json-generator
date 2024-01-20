<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings;

use ItalyStrap\Config\AccessValueInArrayWithNotationTrait;
use ItalyStrap\Tests\Unit\Domain\Input\Settings\CollectionIntegrationTest;
use ItalyStrap\Tests\Unit\Domain\Input\Settings\CollectionTest;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Custom\Custom;

/**
 * @psalm-api
 * @see CollectionTest
 * @see CollectionIntegrationTest
 */
class Collection implements CollectionInterface, \JsonSerializable
{
    use AccessValueInArrayWithNotationTrait;

    /**
     * @var ItemInterface[]
     */
    private array $collection = [];

    private string $field = '';

    public function add(ItemInterface $item): self
    {
        /**
         * The slug method can return a value like this "navbar.min.height"
         * So the key needs to be built before all the insert value in the correct position
         */
        $key = $item->category() . '.' . $item->slug();

        $this->assertIsUnique($key, $item);

        /** @psalm-suppress MixedPropertyTypeCoercion */
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
     * @return array<string, mixed>|ItemInterface|mixed|null
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
         * This prevents to return a string with the placeholder like this:
         * {{color.base}}
         * instead we want to return the value of the placeholder like this:
         * var(--wp--preset--color--base)
         */
        return $this->extractPlaceholders((string)$value);
    }

    public function parse(string $content): string
    {
        return $this->extractPlaceholders($content);
    }

    /**
     * Just a reminder:
     * '/{{([\w.]+)}}|var:(preset|custom)\|([\w.]+)\|([\w.]+)/'
     * The pattern above will match also the shortcut syntax used as a reference by WordPress to search values.
     * For now let the WordPress doing the job, and we will see later if we need to change this.
     */
    private function extractPlaceholders(string $string): string
    {
        $pattern = '/{{([\w.]+)}}/';
        $found = (int)\preg_match_all($pattern, $string, $matches, PREG_SET_ORDER);

        if ($found === 0) {
            return $string;
        }

        $replace = [];
        $search = [];
        foreach ($matches as $match) {
            $search[] = \array_shift($match);

            /**
             * The second parameter is needed to search also in the custom collection
             * Let say you define a custom value `spacer.base`, `spacer` is not any Presets category
             * so in this case the second $this->get() is added as default and will search in the custom collection
             * for `custom.spacer.base` and if found will return the value from the custom collection.
             * If any value in the Preset and Custom collection is found then the null default will be returned.
             *
             * @var ItemInterface|null $item
             */
            $item = $this->get($match[0], $this->get(Custom::CATEGORY . '.' . $match[0]));

            if ($item === null) {
                throw new \RuntimeException(sprintf('{{%s}} does not exists', $match[0]));
            }

            $replace[] = $item->var();
        }

        return \str_replace($search, $replace, $string);
    }

    public function field(string $field): self
    {
        $keys = \array_keys($this->collection);
        if (!\in_array($field, $keys, true)) {
            throw new \InvalidArgumentException(\sprintf(
                'Field %s does not exists in the collection, got: %s',
                $field,
                \implode(', ', $keys)
            ));
        }

        $this->field = $field;
        return $this;
    }

    public function toArray(): array
    {
        $field = $this->field;
        $this->field = '';

        if ($field === '') {
            return $this->collection;
        }

        $fetched = (array)$this->get($field, []);

        if ($field === 'custom') {
            return $this->processCustomCollection($fetched);
        }

        return $this->processPresetCollection($fetched);
    }

    /**
     * @todo Filter empty values from collection
     */
    public function toArrayByCategory(string $category): array
    {
        $this->field($category);
        return $this->toArray();
    }

    private function processPresetCollection(array $collection): array
    {
        return \array_values(
            \array_map(
                function (ItemInterface $item): array {
                    $newItems = [];
                    foreach ($item->toArray() as $key => $value) {
                        if (\is_string($value)) {
                            $value = $this->extractPlaceholders($value);
                        }

                        $newItems[$key] = $value;
                    }

                    return $newItems;
                },
                $collection
            )
        );
    }

    /**
     * @param array<array-key, mixed> $collection
     * @param array-key|string $prefix
     * @return array<array-key, mixed>
     */
    private function processCustomCollection(array $collection, string $prefix = ''): array
    {
        $processed = [];
        /** @var array<array-key, mixed>|ItemInterface $value */
        foreach ($collection as $key => $value) {
            $fullKey = $prefix === '' ? $key : $prefix . '.' . $key;
            if (\is_array($value)) {
                $processed[$key] = $this->processCustomCollection($value, $fullKey);
                continue;
            }

            $processed[$key] = $this->extractPlaceholders((string)$value);
        }

        return $processed;
    }

    private function assertIsUnique(string $key, ItemInterface $item): void
    {
        if ($this->get($key) !== null) {
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

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
