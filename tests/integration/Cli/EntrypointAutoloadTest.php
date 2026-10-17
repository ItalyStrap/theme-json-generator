<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Integration\Cli;

use Codeception\Test\Unit;
use Symfony\Component\Process\Process;

final class EntrypointAutoloadTest extends Unit
{
    private string $theme;

    // phpcs:ignore -- Method from Codeception
    protected function _before(): void
    {
        $this->theme = \sys_get_temp_dir() . '/theme-json-autoload-' . \bin2hex(\random_bytes(8));
        $this->write('style.css', '/* Theme Name: Autoload fixture */');
    }

    // phpcs:ignore -- Method from Codeception
    protected function _after(): void
    {
        $directory = new \RecursiveDirectoryIterator($this->theme, \FilesystemIterator::SKIP_DOTS);
        /** @var iterable<\SplFileInfo> $files */
        $files = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isDir()) {
                \rmdir($file->getPathname());
                continue;
            }

            \unlink($file->getPathname());
        }

        \rmdir($this->theme);
    }

    public function testGlobalCommandLoadsRootAndVariationAttributesWithoutThemeVendor(): void
    {
        $this->configuration();
        $this->write('theme.json.php', <<<'PHP'
<?php
use ItalyStrap\ThemeJsonGenerator\Attribute\Autoload;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;
return #[Autoload('config')]
static function (ThemeJson $themeJson, Fixture\Palette $palette): void {
    $themeJson->title($palette->title());
};
PHP);
        $this->write('styles/night.blue.json.php', <<<'PHP'
<?php
use ItalyStrap\ThemeJsonGenerator\Attribute\Autoload;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;
return #[Autoload('extra')] #[Autoload(__DIR__ . '/../config')]
static function (ThemeJson $themeJson, Fixture\Palette $palette, Fixture\Variation $variation): void {
    $themeJson->title($palette->title() . $variation->suffix());
};
PHP);
        $this->write('styles/extra/Variation.php', <<<'PHP'
<?php
namespace Fixture;
final class Variation { public function suffix(): string { return ' night'; } }
PHP);
        $process = $this->runDump(['--path', $this->theme], \dirname(__DIR__, 3));

        $this->assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        $this->assertTitle('theme.json', 'Mapped');
        $this->assertTitle('styles/night.blue.json', 'Mapped night');
        $this->assertDirectoryDoesNotExist($this->theme . '/vendor');
    }

    public function testSelectedVariationInheritsRootMappingsWithoutExecutingRootOrSiblings(): void
    {
        $this->configuration();
        $this->write('theme.json.php', <<<'PHP'
<?php
use ItalyStrap\ThemeJsonGenerator\Attribute\Autoload;
return #[Autoload(__DIR__ . '/config')]
static function (): void { throw new RuntimeException('Root callback must not execute'); };
PHP);
        $this->write('styles/night.json.php', $this->entrypoint());
        $this->write('styles/unselected.json.php', '<?php throw new RuntimeException("Must not load sibling");');
        $process = $this->runDump(['--file', 'styles/night.json']);

        $this->assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        $this->assertTitle('styles/night.json', 'Mapped');
        $this->assertFileDoesNotExist($this->theme . '/theme.json');
        $this->assertFileDoesNotExist($this->theme . '/styles/unselected.json');
    }

    public function testCliDirectoryOverridesInvalidRootAndVariationAttributes(): void
    {
        $this->configuration();
        $entrypoint = \str_replace(
            'return static',
            "return #[\\ItalyStrap\\ThemeJsonGenerator\\Attribute\\Autoload('missing')] static",
            $this->entrypoint()
        );
        $this->write('theme.json.php', $entrypoint);
        $this->write('styles/night.json.php', $entrypoint);
        $this->write('vendor/autoload.php', '<?php throw new RuntimeException("Fallback must not load");');
        $process = $this->runDump(['--autoload', './config']);

        $this->assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        $this->assertTitle('theme.json', 'Mapped');
        $this->assertTitle('styles/night.json', 'Mapped');
    }

    public function testFallbackLoadsThemeComposerAutoloaderOnlyOnce(): void
    {
        $this->configuration();
        $this->write('theme.json.php', $this->entrypoint());
        $this->write('styles/night.json.php', $this->entrypoint());
        $this->write('vendor/autoload.php', <<<'PHP'
<?php
file_put_contents(__DIR__ . '/loads', 'loaded', FILE_APPEND);
$loader = new Composer\Autoload\ClassLoader();
$loader->addClassMap([
    'Fixture\Palette' => __DIR__ . '/../config/deep/Palette.php',
    'Fixture\Contract' => __DIR__ . '/../config/Contract.php',
    'Fixture\Label' => __DIR__ . '/../config/Label.php',
    'Fixture\Name' => __DIR__ . '/../config/Name.php',
]);
$loader->register();
return $loader;
PHP);
        $process = $this->runDump();

        $this->assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        $this->assertSame('loaded', \file_get_contents($this->theme . '/vendor/loads'));
        $this->assertTitle('theme.json', 'Mapped');
        $this->assertTitle('styles/night.json', 'Mapped');
    }

    public function testInvalidAttributeDoesNotFallBackToComposer(): void
    {
        $this->write('theme.json.php', <<<'PHP'
<?php
return #[\ItalyStrap\ThemeJsonGenerator\Attribute\Autoload('missing')]
static function (): void {};
PHP);
        $this->write('vendor/autoload.php', '<?php file_put_contents(__DIR__ . "/loaded", "yes");');
        $process = $this->runDump();

        $this->assertSame(1, $process->getExitCode());
        $this->assertStringContainsString('Autoload directory', $process->getErrorOutput());
        $this->assertFileDoesNotExist($this->theme . '/vendor/loaded');
        $this->assertFileDoesNotExist($this->theme . '/theme.json');
    }

    public function testInvalidCliDirectoryDoesNotUseValidAttribute(): void
    {
        $this->configuration();
        $this->write('theme.json.php', <<<'PHP'
<?php
return #[\ItalyStrap\ThemeJsonGenerator\Attribute\Autoload('config')]
static function (): void {};
PHP);
        $process = $this->runDump(['--autoload', 'missing']);

        $this->assertSame(1, $process->getExitCode());
        $this->assertStringContainsString('Autoload directory', $process->getErrorOutput());
    }

    public function testEmptyCliDirectoryFails(): void
    {
        $process = $this->runDump(['--autoload=']);
        $this->assertSame(1, $process->getExitCode());
        $this->assertStringContainsString('must not be empty', $process->getErrorOutput());
    }

    public function testDuplicateDeclarationsFailWithoutExecutingFiles(): void
    {
        $this->configuration();
        $this->write('config/duplicate.php', '<?php namespace Fixture; class Palette {}');
        $this->write('theme.json.php', $this->entrypoint());
        $process = $this->runDump(['--autoload', 'config']);

        $this->assertSame(1, $process->getExitCode());
        $this->assertStringContainsString('Ambiguous autoload classes', $process->getErrorOutput());
    }

    public function testLocalComposerProxyUsesSameAttributes(): void
    {
        $this->configuration();
        $this->write('theme.json.php', \str_replace(
            'return static',
            "return #[\\ItalyStrap\\ThemeJsonGenerator\\Attribute\\Autoload('config')] static",
            $this->entrypoint()
        ));
        $project = \dirname(__DIR__, 3);
        $this->write(
            'vendor/autoload.php',
            '<?php return require ' . \var_export($project . '/vendor/autoload.php', true) . ';'
        );
        $this->write('vendor/bin/theme-json', '<?php $_composer_autoload_path = __DIR__ . "/../autoload.php"; require '
            . \var_export($project . '/bin/theme-json', true) . ';');
        $process = new Process([PHP_BINARY, $this->theme . '/vendor/bin/theme-json', 'dump'], $this->theme);
        $process->run();

        $this->assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        $this->assertTitle('theme.json', 'Mapped');
    }

    public function testPlainEntrypointAndDryRunRemainSupported(): void
    {
        $this->write('theme.json.php', <<<'PHP'
<?php
return static function (\ItalyStrap\ThemeJsonGenerator\ThemeJson $themeJson): void { $themeJson->title('Plain'); };
PHP);
        $process = $this->runDump(['--dry-run']);
        $this->assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        $this->assertFileDoesNotExist($this->theme . '/theme.json');

        $process = $this->runDump();
        $this->assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        $this->assertTitle('theme.json', 'Plain');
    }

    public function testUnknownSelectedFileFails(): void
    {
        $process = $this->runDump(['--file', 'styles/missing.json']);
        $this->assertSame(1, $process->getExitCode());
        $this->assertStringContainsString('was not found in the theme', $process->getErrorOutput());
    }

    private function configuration(): void
    {
        $this->write(
            'config/Contract.php',
            '<?php namespace Fixture; interface Contract { public function title(): string; }'
        );
        $this->write('config/Label.php', <<<'PHP'
<?php
namespace Fixture;
trait Label { public function title(): string { return Name::Title->value; } }
PHP);
        $this->write('config/Name.php', '<?php namespace Fixture; enum Name: string { case Title = "Mapped"; }');
        $this->write(
            'config/deep/Palette.php',
            '<?php namespace Fixture; final class Palette implements Contract { use Label; }'
        );
        $this->write('config/side-effect.php', '<?php throw new RuntimeException("Scanner executed a file");');
    }

    private function entrypoint(): string
    {
        return <<<'PHP'
<?php
return static function (\ItalyStrap\ThemeJsonGenerator\ThemeJson $themeJson, \Fixture\Palette $palette): void {
    $themeJson->title($palette->title());
};
PHP;
    }

    private function write(string $path, string $contents): void
    {
        $directory = \dirname($this->theme . '/' . $path);
        if (!\is_dir($directory)) {
            \mkdir($directory, 0777, true);
        }

        \file_put_contents($this->theme . '/' . $path, $contents);
    }

    /** @param list<string> $arguments */
    private function runDump(array $arguments = [], ?string $workingDirectory = null): Process
    {
        $process = new Process(
            [PHP_BINARY, \dirname(__DIR__, 3) . '/bin/theme-json', 'dump', ...$arguments],
            $workingDirectory ?? $this->theme
        );
        $process->run();
        return $process;
    }

    private function assertTitle(string $file, string $title): void
    {
        $contents = \file_get_contents($this->theme . '/' . $file);
        $this->assertIsString($contents);
        $data = \json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        $this->assertIsArray($data);
        $this->assertSame($title, $data['title']);
    }
}
