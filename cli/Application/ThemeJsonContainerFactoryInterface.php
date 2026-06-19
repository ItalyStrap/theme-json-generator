<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Application;

use ItalyStrap\Config\ConfigInterface;

interface ThemeJsonContainerFactoryInterface
{
    /**
     * @return ConfigInterface<array-key, mixed>
     */
    public function execute(callable $entrypoint): ConfigInterface;
}
