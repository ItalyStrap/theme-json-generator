<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Styles;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles\Outline;

class OutlineTest extends UnitTestCase
{
    use CommonTests;

    protected function makeInstance(): Outline
    {
        return new Outline();
    }
}
