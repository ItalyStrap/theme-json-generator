<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application;

/**
 * @psalm-api
 */
class DumpMessage
{
    private string $rootFolder = '';

    private bool $dry_run;

    private string $sassFolder;
    private string $file;

    public function __construct(
        string $rootFolder,
        string $sassFolder,
        bool $dry_run,
        string $file
    ) {
        $this->rootFolder = $rootFolder;
        $this->dry_run = $dry_run;
        $this->sassFolder = $sassFolder;
        $this->file = $file;
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
        return $this->dry_run;
    }

    public function getFile(): string
    {
        return $this->file;
    }
}
