<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Autoload;

use Composer\Autoload\ClassLoader;
use Composer\ClassMapGenerator\ClassMapGenerator;

final readonly class DirectoryAutoloader
{
    public function __construct(private ClassMapGenerator $generator, private ClassLoader $loader)
    {
        $this->generator->avoidDuplicateScans();
    }

    public function register(string $path, string $baseDirectory): void
    {
        if ($path === '') {
            throw new \InvalidArgumentException('The autoload directory must not be empty.');
        }

        $absolute = \str_starts_with($path, '/')
            || \str_starts_with($path, '\\')
            || \preg_match('/^[a-zA-Z]:[\\\\\/]/', $path) === 1;
        $directory = $absolute ? $path : $baseDirectory . DIRECTORY_SEPARATOR . $path;
        $resolved = \realpath($directory);

        if ($resolved === false || !\is_dir($resolved) || !\is_readable($resolved)) {
            throw new \RuntimeException(\sprintf(
                'Autoload directory "%s" does not exist or is not readable.',
                $directory
            ));
        }

        $this->generator->scanPaths($resolved);
        $classMap = $this->generator->getClassMap();
        $ambiguous = $classMap->getAmbiguousClasses();
        if ($ambiguous !== []) {
            throw new \RuntimeException('Ambiguous autoload classes: ' . \implode(', ', \array_keys($ambiguous)));
        }

        $this->loader->addClassMap($classMap->getMap());
        $this->loader->register(true);
    }

    public function unregister(): void
    {
        $this->loader->unregister();
    }
}
