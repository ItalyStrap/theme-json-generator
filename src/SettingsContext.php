<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

final readonly class SettingsContext
{
    /**
     * @param list<string|int> $path
     */
    public function __construct(
        private ThemeJson $themeJson,
        private array $path,
        private bool $insideBlock = false,
    ) {
    }

    public function blocks(string $block): self
    {
        if ($this->insideBlock) {
            throw new \LogicException(\sprintf(
                'Cannot chain "blocks()" after "blocks()": this settings structure is not supported. %s',
                $this->callerLocation(),
            ));
        }

        if (\preg_match('/^[a-z][a-z0-9-]*\/[a-z][a-z0-9-]*$/', $block) !== 1) {
            throw new \InvalidArgumentException(\sprintf(
                'Expected a valid block name, got "%s".',
                $block
            ));
        }

        return new self($this->themeJson, [...$this->path, 'blocks', $block], true);
    }

    public function blockName(): ?string
    {
        if (!$this->insideBlock) {
            return null;
        }

        $blocksIndex = \array_search('blocks', $this->path, true);
        if ($blocksIndex === false) {
            return null;
        }

        $block = $this->path[$blocksIndex + 1] ?? null;
        return \is_string($block) ? $block : null;
    }

    /**
     * @internal
     * @param array<array-key, string|int>|string|int $path
     */
    public function set(array|string|int $path, mixed $value): bool
    {
        return $this->themeJson->set($this->path($path), $value);
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
    public function path(array|string|int $path): array|string
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

            if (\str_starts_with((string) $file, __DIR__ . DIRECTORY_SEPARATOR)) {
                continue;
            }

            return \sprintf('The invalid chain was configured at %s:%d.', $file, $line);
        }

        return 'The configuration location could not be determined.';
    }
}
