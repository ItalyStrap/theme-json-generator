<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application;

class Message
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