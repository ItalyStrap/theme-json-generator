<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands\Utils;

trait RootFolderTrait
{
    private function rootFolder(): string
    {
        $composer = $this->requireComposer();

        /** @var string $vendorPath */
        $vendorPath = $composer->getConfig()->get('vendor-dir');
        return \dirname($vendorPath);
    }
}
