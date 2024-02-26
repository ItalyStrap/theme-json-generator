<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Bus;

use ItalyStrap\Bus\Bus;
use ItalyStrap\Bus\HandlerInterface;
use ItalyStrap\Tests\UnitTestCase;

class BusHandlerProviderTest extends UnitTestCase
{
    private function makeMessage1(): object
    {
        static $message;
        if ($message) {
            return $message;
        }

        $message = new class {
            public function getMessage(): string
            {
                return 'Hello';
            }
        };

        return $message;
    }

    private function makeHandler1(): HandlerInterface
    {
        return new class implements HandlerInterface {
            public function handle(object $message): string
            {
                return $message->getMessage();
            }
        };
    }

    private function makeMessage2(): object
    {
        static $message;
        if ($message) {
            return $message;
        }

        $message = new class {
            public function getMessage(): string
            {
                return 'World';
            }
        };

        return $message;
    }

    private function makeHandler2(): HandlerInterface
    {
        return new class implements HandlerInterface {
            public function handle(object $message): string
            {
                return $message->getMessage();
            }
        };
    }

    private function makeHandlerProvider(): HandlerInterface
    {
        return new class implements HandlerInterface {
            private array $handlers = [];

            /**
             * @param class-string $messageName
             */
            public function addHandler(HandlerInterface $handler, string $messageName): void
            {
                $this->handlers[$messageName] = $handler;
            }

            public function getHandlerForCommand(object $message): HandlerInterface
            {
                $messageClass = \get_class($message);
                if (\array_key_exists($messageClass, $this->handlers)) {
                    return $this->handlers[$messageClass];
                }

                throw new \InvalidArgumentException(\sprintf(
                    'No handler for message %s',
                    $messageClass
                ));
            }

            /**
             * @return mixed
             */
            public function handle(object $message)
            {
                return $this->getHandlerForCommand($message)->handle($message);
            }
        };
    }

    private array $handlers = [];

    private function makeInstance(): Bus
    {
        $handlerProvider = $this->makeHandlerProvider();
        foreach ($this->handlers as $messageName => $handler) {
            $handlerProvider->addHandler($handler, $messageName);
        }

        return new Bus($handlerProvider);
    }

    public function testMessage1(): void
    {
        $this->handlers[\get_class($this->makeMessage1())] = $this->makeHandler1();

        $sut = $this->makeInstance();

        $result = $sut->handle($this->makeMessage1());

        $this->assertSame('Hello', $result);
    }

    public function testMessage2(): void
    {
        $this->handlers[\get_class($this->makeMessage2())] = $this->makeHandler2();

        $sut = $this->makeInstance();

        $result = $sut->handle($this->makeMessage2());

        $this->assertSame('World', $result);
    }
}
