<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands;

/**
 * @psalm-api
 */
class ValidateMessage
{
    private string $rootFolder;

    private string $schemaPath;

    public function __construct(
        string $rootFolder,
        string $schemaPath
    ) {
        $this->schemaPath = $schemaPath;
        $this->rootFolder = $rootFolder;
    }

    public function getSchemaPath(): string
    {
        return $this->schemaPath;
    }

    public function getRootFolder(): string
    {
        return $this->rootFolder;
    }
}
