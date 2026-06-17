<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Typography\Utilities;

use FontLib\Font;
use FontLib\TrueType\File;

final readonly class FontFaceLoaderExperimental
{
    public function __construct(
        private string $directory,
        private string $srcPrefix,
    ) {
    }

    /**
     * @return list<FontFace>
     */
    public function load(): array
    {
        $fontFaces = [];

        foreach ($this->files() as $file) {
            $fontFace = $this->fontFaceFromFile($file);

            if (!$fontFace instanceof FontFace) {
                continue;
            }

            $fontFaces[] = $fontFace;
        }

        return $fontFaces;
    }

    /**
     * @todo Use the ItalyStrap\Finder component instead of this custom implementation.
     * @see https://github.com/italystrap/finder
     * @return list<string>
     */
    private function files(): array
    {
        if (!\is_dir($this->directory)) {
            throw new \InvalidArgumentException(\sprintf(
                'Expected an existing font directory, got "%s".',
                $this->directory
            ));
        }

        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->directory, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if (!$file instanceof \SplFileInfo) {
                continue;
            }

            if (!$file->isFile()) {
                continue;
            }

            if (!$this->isSupportedFontFile($file->getPathname())) {
                continue;
            }

            $files[] = $file->getPathname();
        }

        \sort($files);
        return $files;
    }

    private function fontFaceFromFile(string $file): ?FontFace
    {
        $font = Font::load($file);

        if (!$font instanceof File) {
            throw new \RuntimeException(\sprintf(
                'Unable to load supported font file "%s".',
                $file
            ));
        }

        try {
            $font->parse();

            return new FontFace(
                (string) $font->getFontName(),
                $font->getFontWeight(),
                $this->fontStyle((string) $font->getFontSubfamily()),
                '',
                $this->src($file)
            );
        } finally {
            $font->close();
        }
    }

    private function fontStyle(string $subfamily): string
    {
        $subfamily = \mb_strtolower($subfamily);

        if (\str_contains($subfamily, 'italic')) {
            return 'italic';
        }

        if (\str_contains($subfamily, 'oblique')) {
            return 'oblique';
        }

        return 'normal';
    }

    private function src(string $file): string
    {
        $relativePath = \ltrim(\str_replace($this->normalizedDirectory(), '', $this->normalizedPath($file)), '/');

        return \rtrim($this->srcPrefix, '/') . '/' . $relativePath;
    }

    private function isSupportedFontFile(string $file): bool
    {
        return \in_array(\mb_strtolower((string) \pathinfo($file, PATHINFO_EXTENSION)), [
            'otf',
            'ttf',
            'woff',
            'woff2',
        ], true);
    }

    private function normalizedDirectory(): string
    {
        return \rtrim($this->normalizedPath($this->directory), '/') . '/';
    }

    private function normalizedPath(string $path): string
    {
        return \str_replace('\\', '/', $path);
    }
}
