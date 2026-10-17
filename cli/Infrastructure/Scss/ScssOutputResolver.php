<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Scss;

use ItalyStrap\ThemeJsonGenerator\Attribute\ScssOutput;

final readonly class ScssOutputResolver
{
    public function forEntrypoint(\Closure $entrypoint, \SplFileInfo $file, ?string $override): ?string
    {
        $filename = '_' . $file->getBasename('.json.php') . '.scss';
        if ($override !== null) {
            return $this->resolve($this->directory($override) . '/' . $filename, (string) \getcwd());
        }

        $attributes = (new \ReflectionFunction($entrypoint))->getAttributes(ScssOutput::class);
        if ($attributes === []) {
            return null;
        }

        $configuration = $attributes[0]->newInstance();
        $filename = $configuration->filename ?? $filename;
        if ($filename === '' || \strpbrk($filename, '/\\') !== false) {
            throw new \InvalidArgumentException('SCSS filename must be a filename without directories.');
        }

        return $this->resolve($this->directory($configuration->path) . '/' . $filename, $file->getPath());
    }

    public function resolve(string $path, string $base): string
    {
        if ($path === '' || \str_contains($path, "\0") || \str_contains($path, '://')) {
            throw new \InvalidArgumentException('SCSS output must be a local file path.');
        }

        $path = \str_replace('\\', '/', $path);
        if (!\str_starts_with($path, '/') && \preg_match('/^[A-Za-z]:\//', $path) !== 1) {
            $path = $base . '/' . $path;
        }

        $root = '/';
        if (\preg_match('/^[A-Za-z]:\//', $path) === 1) {
            $root = \substr($path, 0, 3);
            $path = \substr($path, 3);
        }

        $current = $root;
        $parts = \explode('/', $path);
        foreach ($parts as $part) {
            if ($part === '' || $part === '.') {
                continue;
            }

            if ($part === '..') {
                $current = \dirname($current);
                continue;
            }

            $current = \rtrim($current, '/\\') . '/' . $part;
            if (\is_dir($current)) {
                $current = \realpath($current) ?: $current;
            }
        }

        return $current;
    }

    private function directory(string $path): string
    {
        if ($path === '') {
            throw new \InvalidArgumentException('SCSS output directory must not be empty.');
        }

        return \rtrim($path, '/\\');
    }
}
