<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils;

trait RootFolderTrait
{
    private function rootFolder(string $path = ''): string
    {
        return $path !== '' ? $path : (string)\getcwd();
    }
}
