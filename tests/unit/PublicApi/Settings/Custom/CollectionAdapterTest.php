<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Custom;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Custom\CustomToPresets;

final class CollectionAdapterTest extends UnitTestCase
{
    private array $items = [];

    protected function makeInstance(): CustomToPresets
    {
        return new CustomToPresets($this->items);
    }
}
