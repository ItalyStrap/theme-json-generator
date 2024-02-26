<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem;

use ItalyStrap\Config\ConfigInterface;

interface FileWriter
{
    /**
     * @throws \Exception
     */
    public function write(ConfigInterface $data): void;
}
