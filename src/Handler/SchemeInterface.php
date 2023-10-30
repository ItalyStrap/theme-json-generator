<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Handler;

/**
 * @psalm-api
 */
interface SchemeInterface
{
    public function generate(): iterable;
}
