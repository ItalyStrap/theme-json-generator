<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Infrastructure\Filesystem;

use ItalyStrap\Finder\FinderFactory;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\FilesFinder;

class FilesFinderIntegrationTest extends UnitTestCase
{
    private function makeInstance(): FilesFinder
    {
        return new FilesFinder((new FinderFactory())->make());
    }

    public static function extensionProvider(): iterable
    {
        yield 'PHP' => ['php'];

        yield 'JSON' => ['json'];
    }

    /**
     * @dataProvider extensionProvider
     */
    public function testFindFilesInFlatThemeFolder(string $extension): void
    {
        $sut = $this->makeInstance();
        $count = 0;
        foreach (
            $sut->find(
                \codecept_data_dir('fixtures/themes/theme-flat'),
                $extension
            ) as $fileName => $file
        ) {
            $this->assertInstanceOf(\SplFileInfo::class, $file);
            $this->assertSame(FilesFinder::ROOT_FILE_NAME, $fileName);
            ++$count;
        }

        $this->assertSame(1, $count);
    }

    /**
     * @dataProvider extensionProvider
     */
    public function testFindFilesInThemeFolderWithStylesFolder(string $extension): void
    {
        $sut = $this->makeInstance();
        $count = 0;
        foreach (
            $sut->find(
                \codecept_data_dir('fixtures/themes/theme-with-styles'),
                $extension
            ) as $fileName => $file
        ) {
            $this->assertInstanceOf(\SplFileInfo::class, $file);
            ++$count;
        }
    }
}
