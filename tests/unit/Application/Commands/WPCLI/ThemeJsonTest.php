<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Application\Commands\WPCLI;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\WPCLI\Dump;
use Prophecy\Argument;

class ThemeJsonTest extends UnitTestCase
{
    protected function makeInstance(): Dump
    {
        return new Dump();
    }

    public function testItShouldBeInstantiatable(): void
    {
        $sut = $this->makeInstance();
    }
}
