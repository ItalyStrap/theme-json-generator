<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils;

trait FilesFinderTrait
{
    private function findJsonFiles(string $rootFolder): array
    {
        $stylesPath = $rootFolder . '/styles';

        $jsonFilesFound = [
            $rootFolder . '/theme.json',
        ];

        if (\is_dir($stylesPath)) {
            $jsonFilesInStyles = \glob($stylesPath . '/*.json');
            $jsonFilesFound = \array_merge($jsonFilesFound, $jsonFilesInStyles);
        }

        return $jsonFilesFound;
    }

    /**
     * @todo some alternative to file name:
     *       - theme.json.php
     *       - theme.json.dist.php
     */
    private function findPhpFiles(string $rootFolder): array
    {
        $stylesPath = $rootFolder . '/styles';

        $jsonFilesFound = [];
        if (\file_exists($rootFolder . '/theme.json.dist.php')) {
            $jsonFilesFound = [
                $rootFolder . '/theme.json.dist.php',
            ];
        }

        if (\is_dir($stylesPath)) {
            $jsonFilesInStyles = \glob($stylesPath . '/*.php');
            $jsonFilesFound = \array_merge($jsonFilesFound, $jsonFilesInStyles);
        }

        return $jsonFilesFound;
    }
}
