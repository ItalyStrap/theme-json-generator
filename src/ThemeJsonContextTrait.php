<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

trait ThemeJsonContextTrait
{
    /**
     * @internal
     * @param array<array-key, string|int>|string|int $path
     */
    public function set(array|string|int $path, mixed $value): bool
    {
        $this->themeJson->set($this->path($path), $value);
        return true;
    }

    /**
     * @internal
     * @param array<array-key, string|int>|string|int $path
     */
    public function get(array|string|int $path, mixed $default = null): mixed
    {
        return $this->themeJson->get($this->path($path), $default);
    }

    /**
     * @param array<array-key, string|int>|string|int $path
     * @return list<string|int>|string
     */
    private function path(array|string|int $path): array|string
    {
        if (\is_string($path)) {
            return $this->pathAsString() . ($path === '' ? '' : '.' . $path);
        }

        if (\is_int($path)) {
            return [...$this->path, $path];
        }

        return [...$this->path, ...\array_values($path)];
    }

    private function pathAsString(): string
    {
        return \implode('.', \array_map(strval(...), $this->path));
    }

    private function callerLocation(): string
    {
        foreach (\debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS) as $frame) {
            $file = $frame['file'] ?? null;
            $line = $frame['line'] ?? null;
            if (!\is_string($file)) {
                continue;
            }

            if (!\is_int($line)) {
                continue;
            }

            if (\str_starts_with($file, __DIR__ . DIRECTORY_SEPARATOR)) {
                continue;
            }

            return \sprintf('The invalid chain was configured at %s:%d.', $file, $line);
        }

        return 'The configuration location could not be determined.';
    }
}
