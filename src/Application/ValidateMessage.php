<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application;

/**
 * @psalm-api
 */
class ValidateMessage
{
    private string $rootFolder;

    private string $schemaPath;

    private bool $forceRecreate;

    public function __construct(
        string $rootFolder,
        string $schemaPath,
        bool $forceRecreate = false
    ) {
        $this->schemaPath = $schemaPath;
        $this->rootFolder = $rootFolder;
        $this->forceRecreate = $forceRecreate;
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
