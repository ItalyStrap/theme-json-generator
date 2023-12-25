<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils;

trait FilesFinderTrait
{
    private function findJsonFiles(string $rootFolder): iterable
    {
        $stylesPath = $rootFolder . '/styles';

        if (!\is_dir($stylesPath)) {
            yield $rootFolder . '/theme.json';
            return;
        }

        $finder = (new \ItalyStrap\Finder\FinderFactory())->make();
        $found = $finder
            ->in([
                $stylesPath,
            ])
            ->allFiles('*', 'json');

        foreach ($found as $file) {
            yield $file->getPathname();
        }

        yield $rootFolder . '/theme.json';
    }

    /**
     * @todo some alternative to file name:
     *       - theme.json.php
     *       - theme.json.dist.php
     */
    private function findPhpFiles(string $rootFolder): iterable
    {
        $rootPhpEntryPoint = $rootFolder . '/theme.json.dist.php';
        if (\file_exists($rootPhpEntryPoint)) {
            return [];
        }

        $stylesPath = $rootFolder . '/styles';

        if (!\is_dir($stylesPath)) {
            return [
                $rootPhpEntryPoint,
            ];
        }

        $phpFilesFound = (array)\glob($stylesPath . '/*.php');
        $phpFilesFound[] = $rootPhpEntryPoint;

        return $phpFilesFound;
    }
}
