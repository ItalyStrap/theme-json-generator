<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils;

/**
 * TODO: Move this logic into Infrastructure Filesystem layer
 */
trait RootFolderTrait
{
    private function rootFolder(string $path = ''): string
    {
        return $path !== '' ? $path : (string)\getcwd();
    }
}
