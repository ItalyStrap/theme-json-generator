<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Application;

use ItalyStrap\ThemeJsonGenerator\ThemeJson;

interface ThemeJsonContainerFactoryInterface
{
    /**
     * @return ThemeJson<array-key, mixed>
     */
    public function execute(callable $entrypoint): ThemeJson;
}
