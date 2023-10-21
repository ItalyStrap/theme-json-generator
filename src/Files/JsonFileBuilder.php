<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Files;

use Composer\Json\JsonFile;
use ItalyStrap\ThemeJsonGenerator\Composer\ComposerJsonFileAdapter;

class JsonFileBuilder implements FileBuilder
{
    private string $path;

    /**
     * ThemeJsonGenerator constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @throws \Exception
     */
    public function build(callable $callable): void
    {
        $json_file = new ComposerJsonFileAdapter(new JsonFile($this->path));
        /** @psalm-suppress MixedArgument */
        $json_file->write($callable($this->path));
    }
}
