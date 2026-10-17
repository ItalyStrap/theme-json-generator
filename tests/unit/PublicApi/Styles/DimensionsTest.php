<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Styles;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Styles\Dimensions;

final class DimensionsTest extends UnitTestCase
{
    use CommonTests;

    protected function makeInstance(): Dimensions
    {
        return $this->makeStyles()->dimensions();
    }
}
