<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Integration\Cli;

use Codeception\Test\Unit;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Scss\ThemeJsonScss;
use Symfony\Component\Process\Process;

final class ScssExportTest extends Unit
{
    private string $directory;

    // phpcs:ignore -- Method from Codeception
    protected function _before(): void
    {
        $this->directory = \sys_get_temp_dir() . '/theme-json-scss-' . \bin2hex(\random_bytes(8));
        \mkdir($this->directory);
    }

    // phpcs:ignore -- Method from Codeception
    protected function _after(): void
    {
        $directory = new \RecursiveDirectoryIterator($this->directory, \FilesystemIterator::SKIP_DOTS);
        /** @var iterable<\SplFileInfo> $files */
        $files = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isDir() && !$file->isLink()) {
                \rmdir($file->getPathname());
                continue;
            }

            \unlink($file->getPathname());
        }

        \rmdir($this->directory);
    }

    public function testStandaloneCommandNeedsOnlyJsonAndDoesNotLoadPhp(): void
    {
        $this->write('theme.json', '{"settings":{"custom":{"brandColor":"red"}}}');
        $this->write('theme.json.php', '<?php throw new RuntimeException("Do not execute PHP");');
        $this->write('vendor/autoload.php', '<?php throw new RuntimeException("Do not load project vendor");');
        $this->success(['scss']);
        $this->assertStringContainsString('var(--wp--custom--brand-color)', $this->contents('_theme.scss'));
        $this->assertSame('{"settings":{"custom":{"brandColor":"red"}}}', $this->contents('theme.json'));
    }

    public function testDumpAndStandaloneExportProduceIdenticalReferences(): void
    {
        $this->theme();
        $this->write('theme.json.php', $this->entrypoint("#[ScssOutput('assets/scss', '_tokens.scss')]"));
        $this->success(['dump']);
        $this->success(['scss', '--output', 'standalone/_tokens.scss']);
        $this->assertFileEquals($this->path('assets/scss/_tokens.scss'), $this->path('standalone/_tokens.scss'));
        $content = $this->contents('standalone/_tokens.scss');
        $this->assertStringContainsString('var(--wp--preset--color--primary)', $content);
        $this->assertStringContainsString('var(--wp--custom--direct--value)', $content);
    }

    public function testAttributesAreRelativeToEachEntrypointAndNotInherited(): void
    {
        $this->theme();
        $this->write('theme.json.php', $this->entrypoint("#[ScssOutput('assets')]"));
        $this->write('styles/dark.json.php', $this->entrypoint("#[ScssOutput('../assets')]"));
        $this->write('styles/light.json.php', $this->entrypoint());
        $this->success(['dump', '--path', $this->directory], \dirname($this->directory));
        $this->assertFileExists($this->path('assets/_theme.scss'));
        $this->assertFileExists($this->path('assets/_dark.scss'));
        $this->assertFileDoesNotExist($this->path('assets/_light.scss'));
    }

    public function testCommandLineDirectoryOverridesAttributesAndUsesWorkingDirectory(): void
    {
        $this->theme();
        $this->write('theme.json.php', $this->entrypoint("#[ScssOutput('ignored', '_ignored.scss')]"));
        \mkdir($this->path('caller'));
        $this->success(['dump', '--path', $this->directory, '--scss', 'output'], $this->path('caller'));
        $this->assertFileExists($this->path('caller/output/_theme.scss'));
        $this->assertDirectoryDoesNotExist($this->path('ignored'));
    }

    public function testNoScssWinsOverAttributeAndFlag(): void
    {
        $this->theme();
        $this->write('theme.json.php', $this->entrypoint("#[ScssOutput('assets')]"));
        $this->success(['dump', '--scss', 'override', '--no-scss']);
        $this->assertDirectoryDoesNotExist($this->path('assets'));
        $this->assertDirectoryDoesNotExist($this->path('override'));
        $this->assertFileExists($this->path('theme.json'));
    }

    public function testDumpIsOptInAndFileSelectionAppliesToScssToo(): void
    {
        $this->theme();
        $this->write('theme.json.php', $this->entrypoint());
        $this->write('styles/dark.json.php', $this->entrypoint("#[ScssOutput('../assets')]"));
        $this->success(['dump', '--file', 'theme.json']);
        $this->assertDirectoryDoesNotExist($this->path('assets'));
        $this->success(['dump', '--file', 'styles/dark.json']);
        $this->assertFileExists($this->path('assets/_dark.scss'));
        $this->assertFileDoesNotExist($this->path('_theme.scss'));
    }

    public function testDryRunDoesNotWriteJsonScssOrDirectories(): void
    {
        $this->theme();
        $this->write('theme.json.php', $this->entrypoint("#[ScssOutput('assets')]"));
        $process = $this->success(['dump', '--dry-run']);
        $this->assertStringContainsString('would generate SCSS', $process->getOutput());
        $this->assertFileDoesNotExist($this->path('theme.json'));
        $this->assertDirectoryDoesNotExist($this->path('assets'));

        $this->write('theme.json', '{}');
        $this->success(['scss', '--output', 'assets/_tokens.scss', '--dry-run']);
        $this->assertDirectoryDoesNotExist($this->path('assets'));
    }

    public function testCheckDetectsMissingStaleAndCurrentFilesWithoutWriting(): void
    {
        $this->write('theme.json', '{}');
        $this->failure(['scss', '--check'], 'missing or stale');
        $this->assertFileDoesNotExist($this->path('_theme.scss'));
        $this->success(['scss']);
        $this->success(['scss', '--check']);
        $this->write('theme.json', '{"settings":{"custom":{"new":"value"}}}');
        $this->failure(['scss', '--check'], 'missing or stale');
        $this->assertStringNotContainsString('--wp--custom--new', $this->contents('_theme.scss'));
        $this->success(['scss']);
        $this->success(['scss', '--check']);
    }

    public function testUnchangedExportPreservesModificationTime(): void
    {
        $this->write('theme.json', '{}');
        $this->success(['scss']);
        \touch($this->path('_theme.scss'), 1000000000);
        $process = $this->success(['scss']);
        \clearstatcache(true, $this->path('_theme.scss'));
        $this->assertSame(1000000000, \filemtime($this->path('_theme.scss')));
        $this->assertStringContainsString('SCSS unchanged', $process->getOutput());
    }

    public function testConflictingDestinationsFailBeforeWriting(): void
    {
        $this->theme();
        $this->write('theme.json.php', $this->entrypoint("#[ScssOutput('assets', '_shared.scss')]"));
        $this->write('styles/dark.json.php', $this->entrypoint("#[ScssOutput('../assets/./', '_shared.scss')]"));
        $this->failure(['dump'], 'same SCSS file');
        $this->assertDirectoryDoesNotExist($this->path('assets'));
        $this->assertFileDoesNotExist($this->path('theme.json'));
    }

    public function testMalformedJsonAndMissingInputLeaveExistingOutputUntouched(): void
    {
        $this->write('_theme.scss', 'preserve');
        $this->failure(['scss'], 'readable .json');
        $this->write('theme.json', '{invalid');
        $this->failure(['scss'], 'Invalid JSON');
        $this->write('theme.json', '[]');
        $this->failure(['scss'], 'must contain an object');
        $this->assertSame('preserve', $this->contents('_theme.scss'));
    }

    public function testInvalidAndUnwritableDestinationsFailClearly(): void
    {
        $this->write('theme.json', '{}');
        $this->write('blocked', 'file');
        $this->failure(['scss', '--output', 'blocked/_theme.scss'], 'Cannot create SCSS output directory');
        $this->failure(['scss', '--output', 'theme.json'], 'regular .scss file');
        $this->assertSame('{}', $this->contents('theme.json'));
    }

    public function testSymlinkOutputIsRejected(): void
    {
        $this->write('theme.json', '{}');
        $this->write('precious.scss', 'preserve');
        \symlink($this->path('precious.scss'), $this->path('_theme.scss'));
        $this->failure(['scss'], 'regular .scss file');
        $this->assertSame('preserve', $this->contents('precious.scss'));
    }

    public function testSymlinkedDirectoriesCannotHideConflictingDestinations(): void
    {
        $this->theme();
        \mkdir($this->path('assets'));
        \symlink($this->path('assets'), $this->path('alias'));
        $this->write('theme.json.php', $this->entrypoint("#[ScssOutput('assets', '_shared.scss')]"));
        $this->write('styles/dark.json.php', $this->entrypoint("#[ScssOutput('../alias', '_shared.scss')]"));
        $this->failure(['dump'], 'same SCSS file');
        $this->assertFileDoesNotExist($this->path('assets/_shared.scss'));
        $this->assertFileDoesNotExist($this->path('theme.json'));
    }

    public function testDotDotResolvesAfterFollowingDirectorySymlinks(): void
    {
        $this->write('theme.json', '{}');
        \mkdir($this->path('assets/nested'), 0777, true);
        \symlink($this->path('assets/nested'), $this->path('alias'));
        $this->success(['scss', '--output', 'alias/../_tokens.scss']);
        $this->assertFileExists($this->path('assets/_tokens.scss'));
        $this->assertFileDoesNotExist($this->path('_tokens.scss'));
    }

    public function testScssAttributeWorksAlongsideAutoloadAndPipeline(): void
    {
        $this->theme();
        $this->write('theme.json.php', <<<'PHP'
<?php
use ItalyStrap\ThemeJsonGenerator\Attribute\Autoload;
use ItalyStrap\ThemeJsonGenerator\Attribute\ScssOutput;
use ItalyStrap\ThemeJsonGenerator\Pipeline;
return #[Autoload('config')] #[ScssOutput('assets')]
static function (Pipeline $pipeline): void { $pipeline->process([Fixture\Colors::class]); };
PHP);
        $this->write('config/Colors.php', <<<'PHP'
<?php
namespace Fixture;
final class Colors implements \ItalyStrap\ThemeJsonGenerator\ConfiguratorInterface
{
    public function __invoke(\ItalyStrap\ThemeJsonGenerator\ThemeJson $themeJson): void
    {
        $themeJson->settings()->color()->addColor('brand', 'Brand', '#ffffff');
    }
}
PHP);
        $this->success(['dump']);
        $this->assertStringContainsString('var(--wp--preset--color--brand)', $this->contents('assets/_theme.scss'));
    }

    public function testStandaloneVariationUsesItsOwnBasename(): void
    {
        $this->write('styles/dark.contrast.json', '{"settings":{"custom":{"contrast":"red"}}}');
        $this->success(['scss', '--input', 'styles/dark.contrast.json']);
        $this->assertFileExists($this->path('styles/_dark.contrast.scss'));
    }

    public function testInvalidAttributeFilenameFailsBeforeJsonIsWritten(): void
    {
        $this->theme();
        $this->write('theme.json.php', $this->entrypoint("#[ScssOutput('assets', '../theme.json')]"));
        $this->failure(['dump'], 'filename without directories');
        $this->assertFileDoesNotExist($this->path('theme.json'));
    }

    public function testLayoutAndRootPaddingAliasesReferToWordPressDeclarations(): void
    {
        $data = ['version' => 3, 'settings' => [
            'useRootPaddingAwareAlignments' => true,
            'layout' => ['contentSize' => '40rem', 'wideSize' => '80rem'],
        ], 'styles' => ['spacing' => ['padding' => ['top' => '0', 'left' => '1rem']]]];
        $stylesheet = (new \WP_Theme_JSON($data, 'theme'))->get_stylesheet(['styles'], ['theme']);
        $scss = (new ThemeJsonScss())->generate($data);
        \preg_match_all('/var\((--[^)]+)\)/', $scss, $matches);
        $this->assertCount(4, $matches[1]);
        foreach ($matches[1] as $property) {
            $this->assertStringContainsString($property . ':', $stylesheet);
        }
    }

    public function testNamesMatchWordPressForPresetsAndNestedCustomSettings(): void
    {
        $settings = ['color' => ['palette' => []], 'custom' => []];
        foreach (['bodyText', 'XMLParser', 'font2XL', 'foo_bar', 'foo/bar', '1st', "don't", 'café'] as $name) {
            $settings['color']['palette'][] = ['name' => $name, 'slug' => $name, 'color' => '#000000'];
            $settings['custom'][$name] = ['lineHeight' => '1.2'];
        }

        // foo_bar and foo/bar intentionally normalize identically: test each separately.
        foreach ($settings['color']['palette'] as $preset) {
            $data = ['version' => 3, 'settings' => [
                'color' => ['palette' => [$preset]],
                'custom' => [$preset['slug'] => ['lineHeight' => '1.2']],
            ]];
            $stylesheet = (new \WP_Theme_JSON($data, 'theme'))->get_stylesheet(['variables'], ['theme']);
            $scss = (new ThemeJsonScss())->generate($data);
            \preg_match_all('/var\((--[^)]+)\)/', $scss, $matches);
            foreach ($matches[1] as $property) {
                $this->assertStringContainsString($property . ':', $stylesheet, $preset['slug']);
            }
        }
    }

    private function entrypoint(string $attribute = ''): string
    {
        return '<?php use ItalyStrap\\ThemeJsonGenerator\\Attribute\\ScssOutput; return ' . $attribute . <<<'PHP'
 static function (\ItalyStrap\ThemeJsonGenerator\ThemeJson $theme): void {
    $theme->settings()->color()->addColor('primary', 'Primary', '#ffffff');
    $theme->set('settings.custom.direct.value', '1rem');
};
PHP;
    }

    private function theme(): void
    {
        $this->write('style.css', '/* Theme Name: SCSS test */');
    }

    private function path(string $file): string
    {
        return $this->directory . '/' . $file;
    }

    private function contents(string $file): string
    {
        return (string) \file_get_contents($this->path($file));
    }

    private function write(string $file, string $content): void
    {
        $path = $this->path($file);
        if (!\is_dir(\dirname($path))) {
            \mkdir(\dirname($path), 0777, true);
        }

        \file_put_contents($path, $content);
    }

    /** @param list<string> $arguments */
    private function runCommand(array $arguments, ?string $directory = null): Process
    {
        $process = new Process(
            [PHP_BINARY, \dirname(__DIR__, 3) . '/bin/theme-json', ...$arguments],
            $directory ?? $this->directory
        );
        $process->run();
        return $process;
    }

    /** @param list<string> $arguments */
    private function success(array $arguments, ?string $directory = null): Process
    {
        $process = $this->runCommand($arguments, $directory);
        $this->assertSame(0, $process->getExitCode(), $process->getOutput() . $process->getErrorOutput());
        return $process;
    }

    /** @param list<string> $arguments */
    private function failure(array $arguments, string $message): void
    {
        $process = $this->runCommand($arguments);
        $this->assertSame(1, $process->getExitCode(), $process->getOutput() . $process->getErrorOutput());
        $this->assertStringContainsString($message, $process->getOutput() . $process->getErrorOutput());
    }
}
