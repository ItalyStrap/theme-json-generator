<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Autoload;

use ItalyStrap\ThemeJsonGenerator\Attribute\Autoload;

final readonly class EntrypointLoader
{
    public function __construct(private DirectoryAutoloader $autoloader)
    {
    }

    /**
     * @param iterable<string, \SplFileInfo> $files
     * @return \Generator<string, array{\SplFileInfo, \Closure}>
     */
    public function entries(iterable $files, string $rootFolder, ?string $override, string $selected): \Generator
    {
        try {
            yield from $this->loadEntries($files, $rootFolder, $override, $selected);
        } finally {
            $this->autoloader->unregister();
        }
    }

    /**
     * @param iterable<string, \SplFileInfo> $files
     * @return \Generator<string, array{\SplFileInfo, \Closure}>
     */
    private function loadEntries(iterable $files, string $rootFolder, ?string $override, string $selected): \Generator
    {
        if ($override !== null) {
            $this->autoloader->register($override, (string) \getcwd());
        }

        $rootPath = $rootFolder . DIRECTORY_SEPARATOR . 'theme.json.php';
        $rootEntrypoint = \is_file($rootPath) ? $this->read($rootPath) : null;
        $hasRootMappings = $override !== null
            || ($rootEntrypoint !== null && $this->registerAttributes($rootEntrypoint, $rootPath));
        $found = false;

        foreach ($files as $fileName => $file) {
            if (!$this->matches($file, $rootFolder, $selected)) {
                continue;
            }

            $found = true;
            $isRoot = \realpath($file->getPathname()) === \realpath($rootPath);
            $entrypoint = $isRoot && $rootEntrypoint !== null ? $rootEntrypoint : $this->read($file->getPathname());
            $hasMappings = $hasRootMappings;
            if (!$isRoot && $override === null) {
                $hasMappings = $this->registerAttributes($entrypoint, $file->getPathname()) || $hasMappings;
            }

            if (!$hasMappings) {
                $this->loadComposerAutoloader($rootFolder);
            }

            yield $fileName => [$file, $entrypoint];
        }

        if ($selected !== '' && !$found) {
            throw new \RuntimeException(\sprintf('Entrypoint for "%s" was not found in the theme.', $selected));
        }
    }

    private function read(string $path): \Closure
    {
        $entrypoint = require $path;
        if (!\is_callable($entrypoint)) {
            throw new \RuntimeException(\sprintf('Entrypoint "%s" must return a callable.', $path));
        }

        return \Closure::fromCallable($entrypoint);
    }

    private function registerAttributes(\Closure $entrypoint, string $path): bool
    {
        $reflection = new \ReflectionFunction($entrypoint);
        $attributes = $reflection->getAttributes(Autoload::class);
        foreach ($attributes as $attribute) {
            $this->autoloader->register($attribute->newInstance()->path, \dirname($path));
        }

        return $attributes !== [];
    }

    private function loadComposerAutoloader(string $rootFolder): void
    {
        $path = $rootFolder . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
        if (\is_file($path)) {
            require_once $path;
        }
    }

    private function matches(\SplFileInfo $file, string $rootFolder, string $selected): bool
    {
        if ($selected === '') {
            return true;
        }

        $entrypoint = \str_ends_with($selected, '.php') ? $selected : $selected . '.php';
        return \realpath($file->getPathname()) === \realpath($rootFolder . DIRECTORY_SEPARATOR . $entrypoint);
    }
}
