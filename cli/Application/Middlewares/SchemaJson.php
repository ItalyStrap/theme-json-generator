<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Application\Middlewares;

use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\Pipeline\MiddlewareInterface;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\ValidateMessage;
use Webimpress\SafeWriter\FileWriter;

final class SchemaJson implements MiddlewareInterface
{
    /**
     * @phpstan-param ValidateMessage $message
     */
    public function process(object $message, HandlerInterface $handler): int
    {
        $schemaPath = $message->getSchemaPath();
        if (!\file_exists($schemaPath) || $this->isFileSchemaOlderThanOneWeek($schemaPath)) {
            $this->createFileSchema($schemaPath);
        }

        $result = $handler->handle($message);

        if (!\is_int($result)) {
            throw new \RuntimeException('Expected middleware handler to return an integer exit code.');
        }

        return $result;
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
