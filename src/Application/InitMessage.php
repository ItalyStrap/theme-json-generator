<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application;

/**
 * @psalm-api
 */
class InitMessage
{
    private string $rootFolder = '';

    private string $styleOption = '';

    public function __construct(string $rootFolder, string $styleOption)
    {
        $this->rootFolder = $rootFolder;
        $this->styleOption = $styleOption;
    }

    public function getRootFolder(): string
    {
        return $this->rootFolder;
    }

    public function getStyleOption(): string
    {
        return $this->styleOption;
    }
}
