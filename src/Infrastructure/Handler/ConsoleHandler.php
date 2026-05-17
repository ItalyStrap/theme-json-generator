<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Infrastructure\Handler;

use ItalyStrap\Pipeline\CallbackHandler;
use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\Pipeline\MiddlewareInterface;
use ItalyStrap\Pipeline\Pipeline;

final readonly class ConsoleHandler implements HandlerInterface
{
    public const SUCCESS = 0;

    public const FAILURE = 1;

    private Pipeline $pipeline;

    public function __construct(MiddlewareInterface ...$middleware)
    {
        $this->pipeline = new Pipeline(
            new CallbackHandler(
                static fn (object $message): int => self::SUCCESS
            ),
            ...$middleware,
        );
    }

    public function handle(object $message): int
    {
        $result = $this->pipeline->handle($message);

        if (!is_int($result)) {
            throw new \RuntimeException(\sprintf(
                'Expected middleware to return an int exit code, got %s',
                get_debug_type($result),
            ));
        }

        return $result;
    }
}
