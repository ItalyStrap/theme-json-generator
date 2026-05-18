<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Middlewares;

use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\Pipeline\MiddlewareInterface;
use ItalyStrap\ThemeJsonGenerator\Application\ValidateMessage;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Handler\ConsoleHandler;
use Webimpress\SafeWriter\FileWriter;

class SchemaJson implements MiddlewareInterface
{
    /**
     * @phpstan-param ValidateMessage $message
     * @phpstan-param ConsoleHandler $handler
     */
    public function process(object $message, HandlerInterface $handler): int
    {
        $schemaPath = $message->getSchemaPath();
        if (!\file_exists($schemaPath) || $this->isFileSchemaOlderThanOneWeek($schemaPath)) {
            $this->createFileSchema($schemaPath);
        }

        return $handler->handle($message);
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

        FileWriter::writeFile($schemaPath, $schemaContent);
    }
}
