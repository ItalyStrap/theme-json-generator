<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Bus;

use ItalyStrap\Bus\Bus;
use ItalyStrap\Bus\HandlerInterface;
use ItalyStrap\Bus\MiddlewareInterface;
use ItalyStrap\Tests\UnitTestCase;

class BusTest extends UnitTestCase
{
    private function makeInstance(): Bus
    {
        return new Bus(new class implements HandlerInterface {
            public function handle(object $message): int
            {
                $message->getMessage();
                return 1;
            }
        });
    }

    public function testItShouldDoSomething(): void
    {
        $sut = $this->makeInstance();

        $order = [];
        $sut->addMiddleware(new class ($order) implements MiddlewareInterface {
            private array $order;

            public function __construct(array &$order)
            {
                $this->order = &$order;
            }

            public function process(object $message, HandlerInterface $handler)
            {
                $this->order[] = 'Generate';
                return $handler->handle($message);
            }
        });

        $sut->addMiddleware(new class ($order) implements MiddlewareInterface {
            private array $order;

            public function __construct(array &$order)
            {
                $this->order = &$order;
            }

            public function process(object $message, HandlerInterface $handler)
            {
                $this->order[] = 'Validate';
                return $handler->handle($message);
            }
        });

        $result = $sut->handle(new class ($order) {
            private array $order;

            public function __construct(array &$order)
            {
                $this->order = &$order;
            }

            public function getMessage(): string
            {
                $this->order[] = 'Handler called';
                return 'Hello World';
            }
        });

        $this->assertSame(1, $result);
        $this->assertSame(['Generate', 'Validate', 'Handler called'], $order);
    }
}
