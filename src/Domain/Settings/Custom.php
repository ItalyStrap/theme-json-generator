<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings;

use ItalyStrap\Config\AccessValueInArrayWithNotationTrait;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Custom\RawString;

class Custom
{
    use AccessValueInArrayWithNotationTrait;
    use CommonTrait;

    public const CATEGORY = 'custom';

    private array $collection = [];
    private array $testCollection;

    public function __construct(
        array $collection = []
    ) {
        $this->testCollection = $this->collectionToFlat($collection);
        $this->collection = $this->processCollection($collection);
    }

    private function processCollection(array $collection, string $prefix = ''): array
    {
        $processed = [];
        foreach ($collection as $key => $value) {
            $fullKey = $prefix === '' ? $key : $prefix . '.' . $key;
            if (\is_array($value)) {
                $processed[$key] = $this->processCollection($value, $fullKey);
                continue;
            }

            $processed[$key] = new RawString($fullKey, (string)$value);
        }
        return $processed;
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

            if (!\is_string($value)) {
                $processed[] = $value;
                continue;
            }

            $processed[] = new RawString($fullKey, (string)$value);
        }
        return $processed;
    }

    public function get(string $key, $value = null)
    {
        return $this->findValue(
            $this->collection,
            \explode('.', $key),
            $value
        );
    }

    public function toArray(): array
    {
        return $this->collection;
    }

    public function toFlatArray(): array
    {
        return $this->collectionToFlat($this->testCollection);
    }
}
