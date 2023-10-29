<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\ThemeJsonGenerator\Application\Commands\WPCLI;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\WPCLI\ThemeJson;
use Prophecy\Argument;

class ThemeJsonTest extends UnitTestCase
{
    protected function makeInstance(): ThemeJson
    {
        return new ThemeJson();
    }

    public function testItShouldBeInstantiatable(): void
    {
        $sut = $this->makeInstance();
    }
}
