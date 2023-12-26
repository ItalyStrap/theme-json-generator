<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem;

use Composer\Json\JsonFile;
use ItalyStrap\Config\ConfigInterface;

class JsonFileWriter implements FileWriter
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
    public function write(ConfigInterface $data): void
    {
        $json_file = new ComposerJsonFileAdapter(new JsonFile($this->path));

        if (\count($data) === 0) {
            throw new \RuntimeException('No data to write');
        }

        $json_file->write($data->toArray());
    }
}
