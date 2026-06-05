<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Application;

use ItalyStrap\ThemeJsonGenerator\ThemeJson;

interface ThemeJsonContainerFactoryInterface
{
    public function execute(callable $entrypoint): ThemeJson;
}
