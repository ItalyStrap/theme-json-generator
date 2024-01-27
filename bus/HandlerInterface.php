<?php

declare(strict_types=1);

namespace ItalyStrap\Bus;

interface HandlerInterface
{
    /**
     * @return mixed
     */
    public function handle(object $message);
}
