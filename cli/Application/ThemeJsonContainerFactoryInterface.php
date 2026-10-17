<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Cli\Application;

interface ThemeJsonContainerFactoryInterface
{
    public function execute(callable $entrypoint): ThemeJsonBuildResult;
}
