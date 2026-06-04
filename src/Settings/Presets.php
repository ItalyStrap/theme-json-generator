<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\Config\AccessValueInArrayWithNotationTrait;
use ItalyStrap\ThemeJsonGenerator\Settings\Custom\Custom;

/**
 * @see PresetsTest
 * @see PresetsIntegrationTest
 */
final class Presets implements PresetsInterface, \JsonSerializable
{
    use AccessValueInArrayWithNotationTrait;

    /**
     * @var array<array-key, mixed>
     */
    private array $collection = [];

    /**
     * @var array<string, array{category: string, collection: array<array-key, mixed>}>
     */
    private array $scopedCollections = [];

    private string $field = '';

    public function add(PresetInterface $item): self
    {
        /**
         * The slug method can return a value like this "navbar.min.height"
         * So the key needs to be built before all the insert value in the correct position
         * @TODO convert the $key into an array
         */
        $key = $item->type() . '.' . $item->slug();

        $this->assertIsUnique($key, $item);

        $this->insertValue(
            $this->collection,
            \explode('.', $key),
            $item
        );

        return $this;
    }

    /**
     * @TODO Explore if we can move the logic of this method inside the slef::add() method
     *       Eventually the add() method will have this signature add(PresetInterface $item, array|string $path)
     */
    public function addAt(array|string $path, PresetInterface $item): self
    {
        $path = $this->normalizePath($path);

        if (!\str_contains($path, '.blocks.')) {
            return $this->add($item);
        }

        $scope = $this->scopedCollections[$path] ?? [
            'category' => $item->type(),
            'collection' => [],
        ];

        if ($scope['category'] !== $item->type()) {
            throw new \LogicException(\sprintf(
                'Cannot register preset type "%s" at "%s": preset type "%s" is already registered there.',
                $item->type(),
                $path,
                $scope['category'],
            ));
        }

        $key = $item->slug();
        if ($this->findValue($scope['collection'], \explode('.', $key)) !== null) {
            throw new \RuntimeException(\sprintf(
                '%s already registered in %s category at %s',
                $key,
                $item->type(),
                $path,
            ));
        }

        $this->insertValue($scope['collection'], \explode('.', $key), $item);
        $this->scopedCollections[$path] = $scope;

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
            $item = $this->get($match[0], $this->get(Custom::TYPE . '.' . $match[0]));

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

    /**
     * @return array<array-key, mixed>
     */
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

        /** @var PresetInterface[] $fetched */
        return $this->processPresetCollection(...$fetched);
    }

    /**
     * @todo Filter empty values from collection
     * @internal
     */
    public function toArrayByCategory(string $category): array
    {
        $this->field($category);
        return $this->toArray();
    }

    public function toArraysByPath(): array
    {
        $collections = [];
        foreach ($this->scopedCollections as $path => $scope) {
            if ($scope['category'] === Custom::TYPE) {
                $collections[$path] = $this->processCustomCollection($scope['collection']);
                continue;
            }

            /** @var PresetInterface[] $collection */
            $collection = $scope['collection'];
            $collections[$path] = $this->processPresetCollection(...$collection);
        }

        return $collections;
    }

    /**
     * @param PresetInterface ...$collection
     * @return array<int, array<string, mixed>>
     */
    private function processPresetCollection(PresetInterface ...$collection): array
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
     * @param string $prefix
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
                    $item->type(),
                    $key
                )
            );
        }
    }

    /**
     * @param array<array-key, string|int>|string $path
     */
    private function normalizePath(array|string $path): string
    {
        if (\is_string($path)) {
            return $path;
        }

        return \implode('.', \array_map(strval(...), $path));
    }

    /**
     * @return array<array-key, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
