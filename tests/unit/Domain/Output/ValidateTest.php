<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Output;

use ItalyStrap\Pipeline\CallbackHandler;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Application\Middlewares\Validate;
use ItalyStrap\ThemeJsonGenerator\Application\ValidateMessage;
use Prophecy\Argument;

final class ValidateTest extends UnitTestCase
{
    private function makeInstance(): Validate
    {
        return new Validate(
            $this->makeValidator(),
            $this->makeCompiler(),
            $this->makeFilesFinder()
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

        $this->makeInstance()->process(new ValidateMessage('', ''), $this->makeHandler());
    }
}
