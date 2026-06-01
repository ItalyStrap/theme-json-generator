<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

final readonly class StyleContext
{
    /**
     * @var array<string, list<string>>
     */
    private const ALLOWED_TRANSITIONS = [
        'root' => ['blocks', 'elements', 'variations'],
        'blocks' => ['elements', 'variations'],
        'elements' => [],
        'variations' => ['blocks', 'elements'],
    ];

    /**
     * @param list<string|int> $path
     * @param list<string> $structure
     */
    public function __construct(
        private ThemeJson $themeJson,
        private array $path,
        private array $structure = [],
    ) {
    }

    public function at(string|int ...$segments): self
    {
        /** @var list<string|int> $path */
        $path = \array_values([...$this->path, ...$segments]);

        return new self($this->themeJson, $path, $this->structure);
    }

    /**
     * @param array<array-key, string|int>|string $path
     */
    public function blocks(array|string $path): self
    {
        return $this->enter('blocks', $path);
    }

    /**
     * @param array<array-key, string|int>|string $path
     */
    public function elements(array|string $path): self
    {
        return $this->enter('elements', $path);
    }

    public function variations(string $variation): self
    {
        if (\preg_match('/^[a-z][a-z0-9-]*$/', $variation) !== 1) {
            throw new \InvalidArgumentException(\sprintf(
                'Expected a valid variation slug, got "%s".',
                $variation
            ));
        }

        return $this->enter('variations', $variation);
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

    /**
     * @param array<array-key, string|int>|string $path
     */
    private function enter(string $structure, array|string $path): self
    {
        $parent = $this->structure === [] ? 'root' : $this->structure[\array_key_last($this->structure)];

        if (!\in_array($structure, self::ALLOWED_TRANSITIONS[$parent], true)) {
            throw new \LogicException(\sprintf(
                'Cannot chain "%s()" after "%s()": this style structure is not supported. %s',
                $structure,
                $parent,
                $this->callerLocation(),
            ));
        }

        if (\is_string($path)) {
            $path = \explode('.', $path);
        }

        return new self(
            $this->themeJson,
            [...$this->path, $structure, ...$this->normalizePath($path)],
            [...$this->structure, $structure],
        );
    }

    private function callerLocation(): string
    {
        foreach (\debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS) as $frame) {
            $file = $frame['file'] ?? null;
            $line = $frame['line'] ?? null;

            if (!\is_string($file) || !\is_int($line) || \str_starts_with($file, __DIR__ . DIRECTORY_SEPARATOR)) {
                continue;
            }

            return \sprintf('The invalid chain was configured at %s:%d.', $file, $line);
        }

        return 'The configuration location could not be determined.';
    }
}
