<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands\Middleware;

class SchemaJsonMiddleware
{
    public function process(object $command, object $handler): void
    {
        $schemaPath = $command->getSchemaPath();
        if (!\file_exists($schemaPath) || $this->isFileSchemaOlderThanOneWeek($schemaPath)) {
            $this->createFileSchema($schemaPath);
        }

        $handler->handle($command);
    }

    private function isFileSchemaOlderThanOneWeek(string $schemaPath): bool
    {
        $lastModified = \filemtime($schemaPath);
        $oneWeekAgo = \time() - 7 * 24 * 60 * 60;
        return $lastModified < $oneWeekAgo;
    }

    private function createFileSchema(string $schemaPath): void
    {
        $schemaContent = \file_get_contents(
            'https://raw.githubusercontent.com/WordPress/gutenberg/trunk/schemas/json/theme.json'
        );

        if ($schemaContent === false) {
            throw new \RuntimeException("Impossible to download the schema");
        }

        if (!\file_put_contents($schemaPath, $schemaContent)) {
            throw new \RuntimeException("Impossible to write the schema");
        }
    }
}
