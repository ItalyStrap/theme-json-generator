<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Styles;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Styles\Filter;

final class FilterTest extends UnitTestCase
{
    use CommonTests;

    protected function makeInstance(): Filter
    {
        return new Filter();
    }
}
