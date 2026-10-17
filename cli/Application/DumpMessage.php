<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Application;

final readonly class DumpMessage
{
    public function __construct(
        private string $rootFolder,
        public ?string $scssDirectory,
        private bool $dryRun,
        private string $file,
        private ?string $autoload = null,
        public bool $noScss = false
    ) {
    }

    public function getRootFolder(): string
    {
        return $this->rootFolder;
    }

    public function isDryRun(): bool
    {
        return $this->dryRun;
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function getAutoload(): ?string
    {
        return $this->autoload;
    }
}
