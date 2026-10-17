<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Application;

final readonly class ScssMessage
{
    public function __construct(
        public string $input,
        public ?string $output,
        public bool $dryRun,
        public bool $check
    ) {
    }
}
