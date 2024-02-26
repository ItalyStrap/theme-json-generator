<?php

declare(strict_types=1);

namespace ItalyStrap\Bus;

/**
 * @psalm-api
 */
class DecorateBus implements HandlerInterface
{
    private MiddlewareInterface $middleware;
    private HandlerInterface $nextHandler;

    public function __construct(MiddlewareInterface $middleware, HandlerInterface $nextHandler)
    {
        $this->middleware = $middleware;
        $this->nextHandler = $nextHandler;
    }

    public function handle(object $message)
    {
        return $this->middleware->process($message, $this->nextHandler);
    }
}
