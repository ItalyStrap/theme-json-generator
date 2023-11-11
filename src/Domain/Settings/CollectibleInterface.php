<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings;

/**
 * @psalm-api
 */
interface CollectibleInterface
{
    /**
     * @param CollectionInterface ...$collections
     * @return void
     */
    public function withCollection(CollectionInterface ...$collections): void;
}
