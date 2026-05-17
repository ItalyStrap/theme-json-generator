<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem;

class Path
{
    private function cwd(): string
    {
        return (string)\getcwd();
    }
}
