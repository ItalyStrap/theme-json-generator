<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Output\Events;

class GeneratingFile
{
    private string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function getFileName(): string
    {
        return $this->file;
    }
}
