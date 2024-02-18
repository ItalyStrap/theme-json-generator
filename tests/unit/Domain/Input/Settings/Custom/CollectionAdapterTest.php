<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Settings\Custom;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Custom\CustomToPresets;

class CollectionAdapterTest extends UnitTestCase
{
    private array $items = [];

    protected function makeInstance(): CustomToPresets
    {
        return new CustomToPresets($this->items);
    }
}
