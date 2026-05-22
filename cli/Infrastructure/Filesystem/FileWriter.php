<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Filesystem;

use ItalyStrap\Config\ConfigInterface;

interface FileWriter
{
    /**
     * @param ConfigInterface<array-key, mixed> $data
     * @throws \Exception
     */
    public function write(ConfigInterface $data): void;
}
