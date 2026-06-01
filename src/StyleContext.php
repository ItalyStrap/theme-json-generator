<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

final readonly class StyleContext
{
    /**
     * @param list<string|int> $path
     */
    public function __construct(
        private ThemeJson $themeJson,
        private array $path,
    ) {
    }

    public function at(string|int ...$segments): self
    {
        /** @var list<string|int> $path */
        $path = \array_values([...$this->path, ...$segments]);

        return new self($this->themeJson, $path);
    }

    /**
     * @param array<array-key, string|int>|string|int $path
     */
    public function set(array|string|int $path, mixed $value): bool
    {
        return $this->themeJson->set([...$this->path, ...$this->normalizePath($path)], $value);
    }

    /**
     * @param array<array-key, string|int>|string|int $path
     */
    public function get(array|string|int $path, mixed $default = null): mixed
    {
        return $this->themeJson->get([...$this->path, ...$this->normalizePath($path)], $default);
    }

    /**
     * @param array<array-key, string|int>|string|int $path
     * @return list<string|int>
     */
    private function normalizePath(array|string|int $path): array
    {
        if (\is_array($path)) {
            return \array_values($path);
        }

        return [$path];
    }
}
