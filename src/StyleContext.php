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
        'blocks' => ['elements', 'variations', 'state'],
        'elements' => ['state'],
        'variations' => ['blocks', 'elements'],
        'state' => [],
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

    public function blocks(string $path): self
    {
        return $this->enter('blocks', $path);
    }

    public function elements(string $path): self
    {
        return $this->enter('elements', $path);
    }

    public function variations(string $variation): self
    {
        return $this->enter('variations', $variation);
    }

    public function state(string $state): self
    {
        if ($this->structure === []) {
            throw new \LogicException('Cannot call "state()" outside a block or element context.');
        }

        return $this->enter('state', $state, false);
    }

    /**
     * @internal
     */
    public function isScoped(): bool
    {
        return $this->structure !== [];
    }

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

    private function enter(string $structure, string $path, bool $includeStructureInPath = true): self
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

        return new self(
            $this->themeJson,
            [
                ...$this->path,
                ...($includeStructureInPath ? [$structure] : []),
                $path,
            ],
            [...$this->structure, $structure],
        );
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

            if (\str_starts_with((string) $file, __DIR__ . DIRECTORY_SEPARATOR)) {
                continue;
            }

            return \sprintf('The invalid chain was configured at %s:%d.', $file, $line);
        }

        return 'The configuration location could not be determined.';
    }
}
