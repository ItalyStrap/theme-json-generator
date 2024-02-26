<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Output;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\ValidateMessage;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Validate;
use Prophecy\Argument;

class ValidateTest extends UnitTestCase
{
    private function makeInstance(): Validate
    {
        return new Validate(
            $this->makeDispatcher(),
            $this->makeValidator(),
            $this->makeCompiler(),
            $this->makeFilesFinder()
        );
    }

    public function testItShouldHandleButDoNothing(): void
    {
        $this->filesFinder
            ->find(Argument::type('string'), Argument::exact('json'))
            ->willReturn([])
            ->shouldBeCalledOnce();

        $this->makeInstance()->handle(new ValidateMessage('', ''));
    }
}
