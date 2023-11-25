<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Settings\Custom;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Custom\CollectionAdapter;

class CollectionAdapterTest extends UnitTestCase
{
    private array $items = [];

    protected function makeInstance(): CollectionAdapter
    {
        return new CollectionAdapter($this->items);
    }
}
