<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Integration\Cli;

use Codeception\Test\Unit;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;

final class WorkflowTemplateTest extends Unit
{
    private string $directory;

    private string $theme;

    // phpcs:ignore -- Method from Codeception
    protected function _before(): void
    {
        $this->directory = \sys_get_temp_dir() . '/theme-json-workflow-' . \bin2hex(\random_bytes(8));
        $this->theme = $this->directory . '/theme';
        $this->write('theme.json.php', '<?php return static function (): void {};');
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

    public function testCommandCopiesBundledTemplateFromAnotherWorkingDirectory(): void
    {
        $process = new Process([
            PHP_BINARY,
            \dirname(__DIR__, 3) . '/bin/theme-json',
            'workflow:init',
            '--path',
            $this->theme,
        ], $this->directory);
        $process->run();

        $this->assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        $this->assertFileEquals($this->source(), $this->destination());
        $this->assertStringContainsString('THEME_JSON_VERSION', $process->getOutput());
    }

    public function testCommandPreservesAnExistingCustomizedWorkflow(): void
    {
        $first = $this->runCommand();
        $this->assertSame(0, $first->getExitCode(), $first->getErrorOutput());
        \file_put_contents($this->destination(), '# Customized workflow');

        $second = $this->runCommand();

        $this->assertSame(1, $second->getExitCode());
        $this->assertStringContainsString('Workflow already exists', $second->getErrorOutput());
        $this->assertSame('# Customized workflow', \file_get_contents($this->destination()));
    }

    public function testCommandAcceptsAThemeWithOnlyThemeJson(): void
    {
        \unlink($this->theme . '/theme.json.php');
        $this->write('theme.json', '{}');
        $process = $this->runCommand();

        $this->assertSame(0, $process->getExitCode(), $process->getErrorOutput());
        $this->assertFileEquals($this->source(), $this->destination());
    }

    public function testCommandRejectsAnUnrelatedDirectory(): void
    {
        \unlink($this->theme . '/theme.json.php');
        $process = $this->runCommand();

        $this->assertSame(1, $process->getExitCode());
        $this->assertStringContainsString('must contain theme.json', $process->getErrorOutput());
        $this->assertDirectoryDoesNotExist($this->theme . '/.github');
    }

    public function testCommandPreservesAnExistingSymlink(): void
    {
        $this->write('.github/workflows/placeholder', '');
        \symlink($this->directory . '/missing-target', $this->destination());
        $process = $this->runCommand();

        $this->assertSame(1, $process->getExitCode());
        $this->assertTrue(\is_link($this->destination()));
        $this->assertFileDoesNotExist($this->directory . '/missing-target');
    }

    public function testTemplateCommitsOnlyGeneratedOutputsAndSkipsUnchangedBuilds(): void
    {
        $remote = $this->directory . '/remote.git';
        $this->git(['init', '--bare', '--initial-branch=main', $remote]);
        $this->git(['init', '--initial-branch=main']);
        $this->git(['config', 'user.name', 'Workflow test']);
        $this->git(['config', 'user.email', 'workflow@example.invalid']);
        $this->write('.gitignore', "*.json\n");
        $this->write('styles/dark mode.json.php', '<?php return static function (): void {};');
        $this->write('unrelated.txt', 'original');
        $this->git(['add', '.']);
        $this->git(['commit', '-m', 'Fixture']);
        $this->git(['remote', 'add', 'origin', $remote]);
        $this->git(['push', 'origin', 'main']);
        $this->write('theme.json', '{"title":"Generated"}');
        $this->write('styles/dark mode.json', '{"title":"Dark"}');
        $this->write('styles/unrelated.json', '{}');
        $this->write('unrelated.txt', 'changed');

        $script = $this->script('Commit generated JSON files');
        $first = $this->runScript($script);

        $this->assertSame(0, $first->getExitCode(), $first->getErrorOutput());
        $this->assertSame(
            "styles/dark mode.json\ntheme.json",
            \trim($this->git(['show', '--pretty=', '--name-only', 'HEAD']))
        );
        $this->assertSame('unrelated.txt', \trim($this->git(['diff', '--name-only'])));
        $this->assertSame(
            '{"title":"Generated"}',
            $this->git(['--git-dir=' . $remote, 'show', 'main:theme.json'])
        );
        $commit = $this->git(['rev-parse', 'HEAD']);

        $second = $this->runScript($script);

        $this->assertSame(0, $second->getExitCode(), $second->getErrorOutput());
        $this->assertSame($commit, $this->git(['rev-parse', 'HEAD']));
        $this->assertStringContainsString('already up to date', $second->getOutput());
    }

    public function testTemplateRequiresAnExplicitGeneratorVersionBeforeInstallation(): void
    {
        $process = new Process(
            ['bash', '-e', '-o', 'pipefail', '-c', $this->script('Install the generator globally')],
            $this->theme,
            ['THEME_JSON_VERSION' => 'REPLACE_WITH_VERSION']
        );
        $process->run();

        $this->assertSame(1, $process->getExitCode());
        $this->assertStringContainsString('Set THEME_JSON_VERSION', $process->getErrorOutput());
    }

    private function source(): string
    {
        return \dirname(__DIR__, 3) . '/templates/theme-json.yml';
    }

    private function destination(): string
    {
        return $this->theme . '/.github/workflows/theme-json.yml';
    }

    private function runCommand(): Process
    {
        $process = new Process([
            PHP_BINARY,
            \dirname(__DIR__, 3) . '/bin/theme-json',
            'workflow:init',
        ], $this->theme);
        $process->run();
        return $process;
    }

    private function script(string $name): string
    {
        $workflow = Yaml::parseFile($this->source());
        $this->assertIsArray($workflow);
        $jobs = $workflow['jobs'];
        $this->assertIsArray($jobs);
        $job = $jobs['generate'];
        $this->assertIsArray($job);
        $steps = $job['steps'];
        $this->assertIsArray($steps);
        foreach ($steps as $step) {
            $this->assertIsArray($step);
            if (($step['name'] ?? null) === $name) {
                $this->assertIsString($step['run']);
                return $step['run'];
            }
        }

        $this->fail('Workflow step not found: ' . $name);
    }

    private function runScript(string $script): Process
    {
        $process = new Process(
            ['bash', '-e', '-o', 'pipefail', '-c', $script],
            $this->theme,
            ['TARGET_BRANCH' => 'main']
        );
        $process->run();
        return $process;
    }

    /** @param list<string> $arguments */
    private function git(array $arguments): string
    {
        $process = new Process(['git', ...$arguments], $this->theme);
        $process->mustRun();
        return $process->getOutput();
    }

    private function write(string $path, string $contents): void
    {
        $directory = \dirname($this->theme . '/' . $path);
        if (!\is_dir($directory)) {
            \mkdir($directory, 0777, true);
        }

        \file_put_contents($this->theme . '/' . $path, $contents);
    }
}
