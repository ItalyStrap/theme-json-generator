<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Custom;

final readonly class CustomToPresets
{
    /**
     * @param array<string, mixed> $customs
     */
    public function __construct(private array $customs = [])
    {
    }

    /**
     * @return Custom[]
     */
    public function toArray(): array
    {
        return $this->presetsToFlat($this->customs);
    }

    /**
     * @param array<string, mixed> $presets
     * @return Custom[]
     */
    private function presetsToFlat(array $presets, string $prefix = ''): array
    {
        $processed = [];

        /**
         * @var string|array<string, mixed>|\Stringable $value
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
