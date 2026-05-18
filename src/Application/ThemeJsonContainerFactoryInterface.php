<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application;

use ItalyStrap\ThemeJsonGenerator\Api\ThemeJson;

interface ThemeJsonContainerFactoryInterface
{
    /**
     * @return ThemeJson<array-key, mixed>
     */
    public function execute(callable $entrypoint): ThemeJson;
}
