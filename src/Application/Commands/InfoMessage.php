<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands;

/**
 * @psalm-api
 */
class InfoMessage
{
    private string $rootFolder = '';

    public function __construct(string $rootFolder)
    {
        $this->rootFolder = $rootFolder;
    }

    public function getRootFolder(): string
    {
        return $this->rootFolder;
    }
}
