<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Application;

final readonly class DumpMessage
{
    public function __construct(
        private string $rootFolder,
        private string $sassFolder,
        private bool $dryRun,
        private string $file
    ) {
    }

    public function getRootFolder(): string
    {
        return $this->rootFolder;
    }

    public function getSassFolder(): string
    {
        return \rtrim($this->sassFolder, DIRECTORY_SEPARATOR);
    }

    public function isDryRun(): bool
    {
        return $this->dryRun;
    }

    public function getFile(): string
    {
        return $this->file;
    }
}
