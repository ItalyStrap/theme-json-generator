<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Cli\Infrastructure\Filesystem;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Filesystem\ScssFileWriter;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Color;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColor;
use ItalyStrap\ThemeJsonGenerator\Settings\Custom\Custom;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\Settings\Spacing\SpacingSize;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\FontSize;

final class SassFileWriterTest extends UnitTestCase
{
    private string $path;

    protected function makeInstance(): ScssFileWriter
    {
        $this->path = \codecept_output_dir('theme.scss');

        return new ScssFileWriter($this->path);
    }

    public function testItWritesUniqueSortedPropertiesFromPresets(): void
    {
        $presets = new Presets();
        $presets
            ->add(new SpacingSize('50', 'Medium', '1rem'))
            ->add(new Custom('alignment.center', 'center'))
            ->add(new FontSize('bodyText', 'Body', '1rem'))
            ->add(new Color('primary', 'Primary', new CssColor('#ffffff')))
            ->addToBlock('core/group', new Color('primary', 'Primary', new CssColor('#000000')));

        $this->makeInstance()->write($presets);

        $this->assertStringEqualsFile(
            $this->path,
            <<<'SCSS'
$wp--custom--alignment--center: --wp--custom--alignment--center;
$wp--preset--color--primary: --wp--preset--color--primary;
$wp--preset--font-size--body-text: --wp--preset--font-size--body-text;
$wp--preset--spacing--50: --wp--preset--spacing--50;
SCSS
            . \PHP_EOL
        );

        \unlink($this->path);
    }

    public function testItWritesAnEmptyFileWhenThereAreNoPresets(): void
    {
        $this->makeInstance()->write(new Presets());

        $this->assertFileExists($this->path);
        $this->assertSame('', (string) \file_get_contents($this->path));

        \unlink($this->path);
    }

    public function testItWritesAPropertyDefinedOnlyForABlock(): void
    {
        $presets = new Presets();
        $presets->addToBlock(
            'core/group',
            new FontSize('block-title', 'Block title', '2rem')
        );

        $this->makeInstance()->write($presets);

        $this->assertStringEqualsFile(
            $this->path,
            '$wp--preset--font-size--block-title: --wp--preset--font-size--block-title;' . \PHP_EOL
        );

        \unlink($this->path);
    }

    public function testItReplacesAnExistingFile(): void
    {
        $writer = $this->makeInstance();
        \file_put_contents($this->path, 'obsolete');

        $presets = new Presets();
        $presets->add(new Custom('spacing.base', '1rem'));

        $writer->write($presets);

        $this->assertStringEqualsFile(
            $this->path,
            '$wp--custom--spacing--base: --wp--custom--spacing--base;' . \PHP_EOL
        );

        \unlink($this->path);
    }
}
