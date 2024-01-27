<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands\Middleware;

use ItalyStrap\Bus\HandlerInterface;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\ValidateMessage;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Validate;

class DeleteSchemaJsonMiddleware implements \ItalyStrap\Bus\MiddlewareInterface
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
