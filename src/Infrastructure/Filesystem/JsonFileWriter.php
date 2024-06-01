<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem;

use ItalyStrap\Config\ConfigInterface;
use Webimpress\SafeWriter;

class JsonFileWriter implements FileWriter
{
    private string $path;

    /**
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
        if (\count($data) === 0) {
            throw new \RuntimeException('No data to write');
        }

        // This part is borrowed from \Composer\Json\JsonFile
        $json_data = \json_encode(
            $data->toArray(),
            \JSON_UNESCAPED_SLASHES | \JSON_PRETTY_PRINT | \JSON_UNESCAPED_UNICODE
        );
        if (false === $json_data) {
            throw new \RuntimeException('Error encoding JSON data');
        }

        SafeWriter\FileWriter::writeFile($this->path, $json_data . \PHP_EOL);
    }
}
