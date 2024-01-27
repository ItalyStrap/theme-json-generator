<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands\Middleware;

use ItalyStrap\ThemeJsonGenerator\Application\Commands\ValidateMessage;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Validate;

class SchemaJsonMiddleware implements \ItalyStrap\Bus\MiddlewareInterface
{
    public function process(object $message, \ItalyStrap\Bus\HandlerInterface $handler): int
    {
        /** @var ValidateMessage $message */
        $schemaPath = $message->getSchemaPath();
        if (!\file_exists($schemaPath) || $this->isFileSchemaOlderThanOneWeek($schemaPath)) {
            $this->createFileSchema($schemaPath);
        }

        return (int)$handler->handle($message);
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

        $bytesWritten = (int)\file_put_contents($schemaPath, $schemaContent);
        if ($bytesWritten === 0) {
            throw new \RuntimeException("Impossible to write the schema");
        }
    }
}
