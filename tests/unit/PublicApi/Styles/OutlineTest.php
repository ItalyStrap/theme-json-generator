<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Styles;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Styles\Outline;

final class OutlineTest extends UnitTestCase
{
    use CommonTests;

    protected function makeInstance(): Outline
    {
        return $this->makeStyles()->outline();
    }
}
