<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application;

readonly class Message
{
    public function __construct(
        private string $rootFolder
    ) {
    }

    public function getRootFolder(): string
    {
        return $this->rootFolder;
    }
}
