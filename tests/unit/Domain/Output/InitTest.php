<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Output;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Application\Message;
use ItalyStrap\ThemeJsonGenerator\Application\Middlewares\Init;
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

//        $this->makeInstance()->process(new Message(''));
    }
}
