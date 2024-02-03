<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings;

use ItalyStrap\Config\AccessValueInArrayWithNotationTrait;
use ItalyStrap\Tests\Unit\Domain\Input\Settings\PresetsIntegrationTest;
use ItalyStrap\Tests\Unit\Domain\Input\Settings\PresetsTest;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Custom\Custom;

/**
 * @psalm-api
 * @see PresetsTest
 * @see PresetsIntegrationTest
 */
class Presets implements PresetsInterface, \JsonSerializable
{
    use AccessValueInArrayWithNotationTrait;

    /**
     * @var PresetInterface[]
     */
    private array $collection = [];

    private string $field = '';

    public function add(PresetInterface $item): self
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

    public function addMultiple(array $items): self
    {
        foreach ($items as $item) {
            $this->add($item);
        }

        return $this;
    }

    public function get(string $key, $default = null)
    {
        return $this->findValue($this->collection, \explode('.', $key), $default);
    }

    /**
     * Just a reminder:
     * '/{{([\w.]+)}}|var:(preset|custom)\|([\w.]+)\|([\w.]+)/'
     * The pattern above will match also the shortcut syntax used as a reference by WordPress to search values.
     * For now let the WordPress doing the job, and we will see later if we need to change this.
     */
    public function parse(string $content): string
    {
        $pattern = '/{{([\w.]+)}}/';
        $found = (int)\preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        if ($found === 0) {
            return $content;
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
             * @var PresetInterface|null $item
             */
            $item = $this->get($match[0], $this->get(Custom::CATEGORY . '.' . $match[0]));

            if ($item === null) {
                throw new \RuntimeException(sprintf('{{%s}} does not exists', $match[0]));
            }

            $replace[] = $item->var();
        }

        return \str_replace($search, $replace, $content);
    }

    public function field(string $field): self
    {
        if (!\array_key_exists($field, $this->collection)) {
            throw new \RuntimeException(\sprintf(
                'Field %s does not exists in the collection, got: %s',
                $field,
                \implode(', ', \array_keys($this->collection)) ?: 'empty collection'
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
        return \array_values(\array_map(
            function (PresetInterface $item): array {
                $newItems = [];
                foreach ($item->toArray() as $key => $value) {
                    if (\is_string($value)) {
                        $value = $this->parse($value);
                    }

                    $newItems[$key] = $value;
                }

                return $newItems;
            },
            $collection
        ));
    }

    /**
     * @param array<array-key, mixed> $collection
     * @param array-key|string $prefix
     * @return array<array-key, mixed>
     */
    private function processCustomCollection(array $collection, string $prefix = ''): array
    {
        $processed = [];
        /** @var array<array-key, mixed>|PresetInterface $value */
        foreach ($collection as $key => $value) {
            $fullKey = $prefix === '' ? $key : $prefix . '.' . $key;
            if (\is_array($value)) {
                $processed[$key] = $this->processCustomCollection($value, $fullKey);
                continue;
            }

            $processed[$key] = $this->parse((string)$value);
        }

        return $processed;
    }

    private function assertIsUnique(string $key, PresetInterface $item): void
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

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
