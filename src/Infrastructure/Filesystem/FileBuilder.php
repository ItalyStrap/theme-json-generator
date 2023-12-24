<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem;

use ItalyStrap\Config\ConfigInterface;

interface FileBuilder
{
    /**
     * @throws \Exception
     */
    public function write(ConfigInterface $data): void;
}
