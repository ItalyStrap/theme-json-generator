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

    public function testItShouldNotSilentlySkipDiscoveredWoff2FilesThatCannotBeLoaded(): void
    {
        $directory = $this->createTemporaryFontDirectory();
        $file = $directory . '/Inter.woff2';
        \file_put_contents($file, 'wOF2' . \str_repeat("\0", 44));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to load supported font file');

        (new FontFaceLoaderExperimental($directory, 'file:./fixtures/fonts'))->load();
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
            foreach (new \FilesystemIterator($this->temporaryFontDirectory) as $file) {
                if ($file instanceof \SplFileInfo && $file->isFile()) {
                    \unlink($file->getPathname());
                }
            }

            \rmdir($this->temporaryFontDirectory);
            $this->temporaryFontDirectory = '';
        }

        parent::_after();
    }
}
