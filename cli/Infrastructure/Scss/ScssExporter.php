<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Scss;

use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Filesystem\ScssFileWriter;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Output\ConsoleOutput;

final readonly class ScssExporter
{
    public function __construct(private ThemeJsonScss $generator)
    {
    }

    /** @param array<array-key, mixed> $data */
    public function content(array $data): string
    {
        return $this->generator->generate($data);
    }

    public function export(string $path, string $content, bool $dryRun = false, bool $check = false): int
    {
        $writer = new ScssFileWriter($path);
        $output = new ConsoleOutput();
        $label = OutputFormatter::escape($path);
        if ($check) {
            $current = $writer->isCurrent($content);
            $output->writeln(($current ? 'SCSS is current: ' : 'SCSS is missing or stale: ') . $label);
            return $current ? 0 : 1;
        }

        if ($dryRun) {
            $output->writeln('Dry run: would generate SCSS ' . $label);
            return 0;
        }

        if ($writer->isCurrent($content)) {
            $output->writeln('SCSS unchanged: ' . $label);
            return 0;
        }

        $writer->write($content);
        $output->writeln('Generated SCSS: ' . $label);
        return 0;
    }
}
