<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Handler;

interface SchemeInterface
{
    public function generate(): iterable;
}
