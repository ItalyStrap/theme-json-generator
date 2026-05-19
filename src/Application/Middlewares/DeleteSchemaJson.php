<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Middlewares;

use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\Pipeline\MiddlewareInterface;
use ItalyStrap\ThemeJsonGenerator\Application\ValidateMessage;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Handler\ConsoleHandler;

final class DeleteSchemaJson implements MiddlewareInterface
{
    /**
     * @phpstan-param ValidateMessage $message
     * @phpstan-param ConsoleHandler $handler
     */
    public function process(object $message, HandlerInterface $handler): int
    {
        $schemaPath = $message->getSchemaPath();
        if ($message->shouldRecreate() && \file_exists($schemaPath)) {
            \unlink($schemaPath);
        }

        return $handler->handle($message);
    }
}
