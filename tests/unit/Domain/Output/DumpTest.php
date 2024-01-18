<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Output;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\DumpMessage;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Dump;
use Prophecy\Argument;

class DumpTest extends UnitTestCase
{
    private function makeInstance(): Dump
    {
        return new Dump(
            $this->makeDispatcher(),
            $this->makeConfig(),
            $this->makeFilesFinder(),
        );
    }

    public function testItShouldHandleButDoNothing(): void
    {
        $this->filesFinder
            ->find(Argument::type('string'), Argument::exact('php'))
            ->willReturn([])
            ->shouldBeCalledOnce();

        $this->makeInstance()->handle(new DumpMessage('', '', false));
    }
}
