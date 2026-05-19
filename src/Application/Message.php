<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application;

final readonly class Message
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
