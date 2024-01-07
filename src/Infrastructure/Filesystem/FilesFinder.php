<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem;

use ItalyStrap\Finder\FinderInterface;

class FilesFinder
{
    public const ROOT_FILE_NAME = 'theme';

    private FinderInterface $finder;

    public function __construct(
        FinderInterface $finder
    ) {
        $this->finder = $finder;
    }

    /**
     * @todo some alternative to file name:
     * - theme.json.php
     * - theme.json.dist.php
     *
     * @return iterable<\SplFileInfo>
     */
    public function find(
        string $rootFolder,
        string $extension
    ): iterable {
        $rootFile = $rootFolder . DIRECTORY_SEPARATOR . self::ROOT_FILE_NAME . '.' . $extension;

        $rootFileInfo = new \SplFileInfo($rootFile);
        if ($rootFileInfo->isFile()) {
            yield $this->extractFileName($rootFileInfo) => $rootFileInfo;
        }

        $stylesFolder = $rootFolder . '/styles';

        if (!\is_dir($stylesFolder)) {
            return;
        }

        $found = $this->finder
            ->in([
                $stylesFolder,
            ])
            ->allFiles(['*', 'dist'], $extension, '.');

        foreach ($found as $file) {
            yield $this->extractFileName($file) => $file;
        }
    }

    private function extractFileName(\SplFileInfo $file): string
    {
        return (string)\explode('.', $file->getBasename())[0];
    }
}
