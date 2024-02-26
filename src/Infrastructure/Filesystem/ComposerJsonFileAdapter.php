<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem;

use Composer\Json\JsonFile;

final class ComposerJsonFileAdapter
{
    private JsonFile $jsonFile;

    /**
     * FileJsonAdapter constructor.
     * @param JsonFile $jsonFile
     */
    public function __construct(JsonFile $jsonFile)
    {
        $this->jsonFile = $jsonFile;
    }

    /**
     * @param array<int|string, mixed> $input_data
     * @param int $options
     * @throws \Exception
     */
    public function write(array $input_data, int $options = 448): void
    {
        $this->jsonFile->write($input_data, $options);
    }
}
