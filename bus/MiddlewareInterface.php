<?php

declare(strict_types=1);

namespace ItalyStrap\Bus;

interface MiddlewareInterface
{
    /**
     * @return mixed
     */
    public function process(object $message, HandlerInterface $handler);
}
