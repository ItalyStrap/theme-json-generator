<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Typography;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\Utilities\FontFace;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\Utilities\FontFaceLoaderExperimental;

final class FontsExperimentalTest extends UnitTestCase
{
    private string $temporaryFontDirectory = '';

    public function testItShouldLoadFontFaceObjectsFromADirectory(): void
    {
        $loader = new FontFaceLoaderExperimental(
            \codecept_data_dir('fixtures/fonts/Roboto'),
            'file:./fixtures/fonts/Roboto'
        );

        $fontFaces = $loader->load();

        $this->assertCount(12, $fontFaces);
        $this->assertContainsOnlyInstancesOf(FontFace::class, $fontFaces);
        $this->assertSame(
            [
                'fontFamily' => 'Roboto Black',
                'fontWeight' => 900,
                'fontStyle' => 'normal',
                'fontDisplay' => 'fallback',
                'src' => 'file:./fixtures/fonts/Roboto/Roboto-Black.ttf',
            ],
            $fontFaces[0]->toArray()
        );
    }

    public function testItShouldInferItalicStyleFromTheFontSubfamily(): void
    {
        $loader = new FontFaceLoaderExperimental(
            \codecept_data_dir('fixtures/fonts/Roboto'),
            'file:./fixtures/fonts/Roboto'
        );

        $fontFaces = $loader->load();

        $this->assertSame('italic', $fontFaces[1]->toArray()['fontStyle']);
        $this->assertSame('file:./fixtures/fonts/Roboto/Roboto-BlackItalic.ttf', $fontFaces[1]->toArray()['src']);
    }

    public function testItShouldStripOnlyTheLeadingDirectoryPrefixFromTheSource(): void
    {
        $directory = $this->createTemporaryFontDirectory();
        $relativeDirectory = \ltrim($directory, '/');
        $nestedDirectory = $directory . '/nested/' . $relativeDirectory;
        \mkdir($nestedDirectory, 0777, true);
        \copy(
            \codecept_data_dir('fixtures/fonts/Roboto/Roboto-Regular.ttf'),
            $nestedDirectory . '/Roboto-Regular.ttf'
        );

        $fontFaces = (new FontFaceLoaderExperimental($directory, 'file:./fonts'))->load();

        $this->assertSame(
            'file:./fonts/nested/' . $relativeDirectory . '/Roboto-Regular.ttf',
            $fontFaces[0]->toArray()['src']
        );
    }

    public function testItShouldContinueLoadingAfterACorruptFontFile(): void
    {
        $directory = $this->createTemporaryFontDirectory();
        \file_put_contents($directory . '/Corrupt.woff2', 'wOF2' . \str_repeat("\0", 44));
        \copy(
            \codecept_data_dir('fixtures/fonts/Roboto/Roboto-Regular.ttf'),
            $directory . '/Roboto-Regular.ttf'
        );

        $fontFaces = (new FontFaceLoaderExperimental($directory, 'file:./fixtures/fonts'))->load();

        $this->assertCount(1, $fontFaces);
        $this->assertSame('Roboto', $fontFaces[0]->toArray()['fontFamily']);
    }

    public function testItShouldRejectMissingDirectories(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected an existing font directory');

        (new FontFaceLoaderExperimental(
            \codecept_data_dir('fixtures/fonts/Missing'),
            'file:./fixtures/fonts/Missing'
        ))->load();
    }

    private function createTemporaryFontDirectory(): string
    {
        $directory = \sys_get_temp_dir() . '/theme-json-generator-fonts-' . \bin2hex(\random_bytes(8));

        \mkdir($directory);
        $this->temporaryFontDirectory = $directory;

        return $directory;
    }

    // phpcs:ignore -- Method from Codeception
    protected function _after(): void
    {
        if ($this->temporaryFontDirectory !== '') {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(
                    $this->temporaryFontDirectory,
                    \FilesystemIterator::SKIP_DOTS
                ),
                \RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($iterator as $file) {
                if (!$file instanceof \SplFileInfo) {
                    continue;
                }

                if ($file->isFile()) {
                    \unlink($file->getPathname());
                    continue;
                }

                \rmdir($file->getPathname());
            }

            \rmdir($this->temporaryFontDirectory);
            $this->temporaryFontDirectory = '';
        }

        parent::_after();
    }
}
