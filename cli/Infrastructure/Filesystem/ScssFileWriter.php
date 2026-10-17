<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Filesystem;

use Webimpress\SafeWriter\FileWriter;

final readonly class ScssFileWriter
{
    public function __construct(private string $path)
    {
        if (!\str_ends_with($path, '.scss') || \is_dir($path) || \is_link($path)) {
            throw new \InvalidArgumentException('SCSS output must be a regular .scss file: ' . $path);
        }
    }

    public function isCurrent(string $content): bool
    {
        if (!\is_file($this->path)) {
            return false;
        }

        $existing = \file_get_contents($this->path);
        if ($existing === false) {
            throw new \RuntimeException('Cannot read SCSS output: ' . $this->path);
        }

        return $existing === $content;
    }

    public function write(string $content): void
    {
        $directory = \dirname($this->path);
        if (!\is_dir($directory) && !@\mkdir($directory, 0777, true) && !\is_dir($directory)) {
            throw new \RuntimeException('Cannot create SCSS output directory: ' . $directory);
        }

        if (!\is_writable($directory)) {
            throw new \RuntimeException('SCSS output directory is not writable: ' . $directory);
        }

        FileWriter::writeFile($this->path, $content);
    }
}
