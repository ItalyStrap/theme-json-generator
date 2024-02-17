<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Output;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\InitMessage;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Init;
use Prophecy\Argument;

class InitTest extends UnitTestCase
{
    private function makeInstance(): Init
    {
        return new Init(
            $this->makeDispatcher(),
            $this->makeFilesFinder(),
        );
    }

    public function testItShouldHandleButDoNothing(): void
    {
        $this->filesFinder
            ->find(Argument::type('string'), Argument::exact('json'))
            ->willReturn([])
            ->shouldBeCalledOnce();

        $this->makeInstance()->handle(new InitMessage('', ''));
    }
}
