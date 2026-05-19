<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Output;

use ItalyStrap\Pipeline\CallbackHandler;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Application\Message;
use ItalyStrap\ThemeJsonGenerator\Application\Middlewares\Init;
use Prophecy\Argument;

final class InitTest extends UnitTestCase
{
    private function makeInstance(): Init
    {
        return new Init(
            $this->makeFilesFinder(),
        );
    }

    private function makeHandler(): CallbackHandler
    {
        return new CallbackHandler(static fn (object $message): int => 0);
    }

    public function testItShouldHandleButDoNothing(): void
    {
        $this->filesFinder
            ->find(Argument::type('string'), Argument::exact('json'))
            ->willReturn([])
            ->shouldBeCalledOnce();

        $this->makeInstance()->process(new Message(''), $this->makeHandler());
    }
}
