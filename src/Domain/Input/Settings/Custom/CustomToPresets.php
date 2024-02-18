<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Custom;

/**
 * @psalm-api
 */
class CustomToPresets
{
    /**
     * @var array<string, mixed>
     */
    private array $customs = [];

    /**
     * @param array<string, mixed> $customs
     */
    public function __construct(
        array $customs = []
    ) {
        $this->customs = $customs;
    }

    public function toArray(): array
    {
        return $this->collectionToFlat($this->customs);
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
