<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

trait ScopedSettingsWriterTrait
{
    /**
     * @param array<array-key, string|int>|string $path
     */
    private function set(array|string $path, mixed $value): bool
    {
        return $this->settings->write($this->scopedPath($path), $value);
    }

    private function setBoolean(string $property, bool $value): self
    {
        $this->set($property, $value);
        return $this;
    }

    /**
     * @param array<array-key, string|int>|string $path
     * @return array<array-key, string|int>|string
     */
    private function scopedPath(array|string $path): array|string
    {
        if (\is_string($path)) {
            return self::SECTION . '.' . $path;
        }

        return [self::SECTION, ...\array_values($path)];
    }
}
