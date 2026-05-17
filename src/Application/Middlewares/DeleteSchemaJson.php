<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Middlewares;

use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\Pipeline\MiddlewareInterface;
use ItalyStrap\ThemeJsonGenerator\Application\ValidateMessage;

class DeleteSchemaJson implements MiddlewareInterface
{
    public function process(object $message, HandlerInterface $handler): int
    {
        /** @var ValidateMessage $message */
        $schemaPath = $message->getSchemaPath();
        if ($message->shouldRecreate() && \file_exists($schemaPath)) {
            \unlink($schemaPath);
        }

        return (int)$handler->handle($message);
    }
}
