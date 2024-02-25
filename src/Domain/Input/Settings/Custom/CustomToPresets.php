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
        return $this->presetsToFlat($this->customs);
    }

    private function presetsToFlat(array $presets, string $prefix = ''): array
    {
        $processed = [];

        /**
         * @var string|array|\Stringable $value
         */
        foreach ($presets as $key => $value) {
            $fullKey = (string)($prefix === '' ? $key : $prefix . '.' . $key);
            if (\is_array($value)) {
                $processed = \array_merge($processed, $this->presetsToFlat($value, $fullKey));
                continue;
            }

            $processed[] = new Custom($fullKey, (string)$value);
        }

        return $processed;
    }
}
