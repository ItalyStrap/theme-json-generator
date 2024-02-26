<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils;

trait DataFromJsonTrait
{
    private function associativeFromPath(string $path): array
    {
        return (array)$this->fromPath($path, true);
    }

    private function objectFromPath(string $path): object
    {
        return (object)$this->fromPath($path, false);
    }

    /**
     * @return array<array-key, mixed>|object
     */
    private function fromPath(string $path, ?bool $isAssociative)
    {
        $json = \file_get_contents($path);
        if ($json === false) {
            throw new \RuntimeException(\sprintf(
                'Unable to read file "%s"',
                $path
            ));
        }

        try {
            $data = \json_decode($json, $isAssociative, 512, JSON_THROW_ON_ERROR);

            if (!\is_array($data) && !\is_object($data)) {
                throw new \RuntimeException(\sprintf(
                    'Unable to decode json from file "%s"',
                    $path
                ));
            }
        } catch (\JsonException $jsonException) {
            throw new \RuntimeException(\sprintf(
                'Unable to decode json from file "%s"',
                $jsonException->getMessage()
            ), $jsonException->getCode(), $jsonException);
        }

        return $data;
    }
}
