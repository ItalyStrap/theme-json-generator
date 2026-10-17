<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Filesystem;

final readonly class WorkflowTemplate
{
    public function __construct(private string $source)
    {
    }

    public function copyTo(string $themeDirectory): string
    {
        $root = \realpath($themeDirectory);
        if ($root === false || !\is_dir($root)) {
            throw new \RuntimeException('The theme directory does not exist: ' . $themeDirectory);
        }

        if (!\is_file($root . '/theme.json') && !\is_file($root . '/theme.json.php')) {
            throw new \RuntimeException('The theme directory must contain theme.json or theme.json.php.');
        }

        $destination = $root . '/.github/workflows/theme-json.yml';
        if (\file_exists($destination) || \is_link($destination)) {
            throw new \RuntimeException('Workflow already exists; keeping your file unchanged: ' . $destination);
        }

        $contents = \file_get_contents($this->source);
        if ($contents === false) {
            throw new \RuntimeException('Unable to read the workflow template: ' . $this->source);
        }

        $directory = \dirname($destination);
        if (!\is_dir($directory) && !\mkdir($directory, 0777, true) && !\is_dir($directory)) {
            throw new \RuntimeException('Unable to create the workflow directory: ' . $directory);
        }

        $this->writeNewFile($destination, $contents);
        return $destination;
    }

    private function writeNewFile(string $destination, string $contents): void
    {
        // Exclusive creation also prevents overwriting a file created after the existence check.
        $stream = @\fopen($destination, 'x');
        if ($stream === false) {
            throw new \RuntimeException('Unable to create a new workflow file: ' . $destination);
        }

        try {
            if (\fwrite($stream, $contents) !== \strlen($contents)) {
                \unlink($destination);
                throw new \RuntimeException('Unable to write the workflow template: ' . $destination);
            }
        } finally {
            \fclose($stream);
        }
    }
}
