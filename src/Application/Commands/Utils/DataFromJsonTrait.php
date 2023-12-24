<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils;

trait DataFromJsonTrait
{
    private function associativeFromPath(string $path): array
    {
        return $this->fromPath($path, true);
    }

    private function objectFromPath(string $path): object
    {
        return $this->fromPath($path, false);
    }

    /**
     * @return array<mixed>|object
     */
    private function fromPath(string $path, ?bool $associative)
    {
        $json = \file_get_contents($path);
        if ($json === false) {
            throw new \RuntimeException(\sprintf(
                'Unable to read file "%s"',
                $path
            ));
        }

        try {
            $data = \json_decode($json, $associative, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new \RuntimeException(\sprintf(
                'Unable to decode json from file "%s"',
                $e->getMessage()
            ));
        }

        return $data;
    }
}
