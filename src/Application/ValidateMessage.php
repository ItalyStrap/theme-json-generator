<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application;

readonly class ValidateMessage
{
    public function __construct(
        private string $rootFolder,
        private string $schemaPath,
        private bool $forceRecreate = false
    ) {
    }

    public function getSchemaPath(): string
    {
        return $this->schemaPath;
    }

    public function getRootFolder(): string
    {
        return $this->rootFolder;
    }

    public function shouldRecreate(): bool
    {
        return $this->forceRecreate;
    }
}
