<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem;

use Composer\Json\JsonFile;

class JsonFileWriter implements FileBuilder
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

        $result = (array)$callable($this->path);

        if (\count($result) === 0) {
            throw new \RuntimeException('The theme.json is empty');
        }

        $json_file->write($result);
    }
}
