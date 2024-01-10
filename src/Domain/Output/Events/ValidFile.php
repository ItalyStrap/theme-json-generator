<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Output\Events;

class ValidFile
{
    private \SplFileInfo $file;

    public function __construct(\SplFileInfo $file)
    {
        $this->file = $file;
    }

    public function getFile(): \SplFileInfo
    {
        return $this->file;
    }
}
