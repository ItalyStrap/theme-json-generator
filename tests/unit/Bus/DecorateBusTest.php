<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Bus;

use ItalyStrap\Bus\Bus;
use ItalyStrap\Bus\DecorateBus;
use ItalyStrap\Tests\UnitTestCase;

class DecorateBusTest extends UnitTestCase
{
    public function testDecorateBus(): void
    {
        $sut = new Bus(new DecorateBus(
            new class implements \ItalyStrap\Bus\MiddlewareInterface {
                public function process(object $message, \ItalyStrap\Bus\HandlerInterface $handler)
                {
                    if ($message->getMessage() === 'Hello') {
                        return $handler->handle($message);
                    }

                    return 0;
                }
            },
            new class implements \ItalyStrap\Bus\HandlerInterface {
                public function handle(object $message)
                {
                    return 1;
                }
            }
        ));

        $result = $sut->handle(new class {
            public function getMessage(): string
            {
                return 'Hello';
            }
        });

        $this->assertSame(1, $result);
    }
}
