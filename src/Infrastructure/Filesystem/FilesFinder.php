<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem;

use ItalyStrap\Finder\FinderInterface;

/**
 * @psalm-api
 */
class FilesFinder
{
    public const ROOT_FILE_NAME = 'theme';

    public const STYLES_FOLDER = 'styles';

    public const JSON_FILE_SUFFIX = '.json';

    private FinderInterface $finder;

    public function __construct(
        FinderInterface $finder
    ) {
        $this->finder = $finder;
    }

    /**
     * @return iterable<string, \SplFileInfo>
     */
    public function find(
        string $rootFolder,
        string $extension
    ): iterable {
        $maybeWithJsonExtensionToo = '';
        if ($extension === 'php') {
            $maybeWithJsonExtensionToo = 'json';
        }

        $rootFile = \implode('.', \array_filter([
            $rootFolder . DIRECTORY_SEPARATOR . self::ROOT_FILE_NAME,
            $maybeWithJsonExtensionToo,
            $extension,
        ]));

        $rootFileInfo = new \SplFileInfo($rootFile);
        if ($rootFileInfo->isFile()) {
            yield $this->extractFileName($rootFileInfo) => $rootFileInfo;
        }

        $stylesFolder = $rootFolder . DIRECTORY_SEPARATOR . self::STYLES_FOLDER;

        if (!\is_dir($stylesFolder)) {
            return;
        }

        /**
         * @var \SplFileInfo[] $files
         */
        $files = $this->finder
            ->in([
                $stylesFolder,
            ])
            ->allFiles(['*'], $extension, '.');

        foreach ($files as $file) {
            yield $this->extractFileName($file) => $file;
        }
    }

    public function resolveJsonFile(\SplFileInfo $file): string
    {
        $fileName = $this->extractFileName($file);
        $themeRoot = \getcwd();
        $stylesFolder = '';
        if ($fileName !== self::ROOT_FILE_NAME) {
            $stylesFolder = self::STYLES_FOLDER;
        }

        $styleCss = \implode(DIRECTORY_SEPARATOR, [
            $themeRoot,
            'style.css',
        ]);

        if (!\file_exists($styleCss)) {
            throw new \RuntimeException('The style.css file was not found in the root folder');
        }

        $styleCssContent = \file_get_contents($styleCss);
        if (\strpos($styleCssContent, 'Theme Name:') === false) {
            throw new \RuntimeException('The style.css file is not a valid WordPress theme');
        }

        return \implode(DIRECTORY_SEPARATOR, \array_filter([
            $themeRoot,
            $stylesFolder,
            $fileName . self::JSON_FILE_SUFFIX,
        ]));
    }

    private function extractFileName(\SplFileInfo $file): string
    {
        return \explode('.', $file->getBasename())[0];
    }
}
