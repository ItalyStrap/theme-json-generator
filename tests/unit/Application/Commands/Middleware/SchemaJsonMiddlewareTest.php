<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Application\Commands\Middleware;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Middleware\SchemaJsonMiddleware;

class SchemaJsonMiddlewareTest extends UnitTestCase
{
    private function makeInstance(): SchemaJsonMiddleware
    {
        return new SchemaJsonMiddleware();
    }

    public function testInstance()
    {
        $actual = $this->makeInstance();
        $this->assertInstanceOf(SchemaJsonMiddleware::class, $actual);
    }

    public function testProcess()
    {
        $message = new class {
            public function getSchemaPath(): string
            {
                return \codecept_output_dir('theme.schema.json');
            }
        };

        $handler = new class implements \ItalyStrap\Bus\HandlerInterface {
            public function handle(object $message): int
            {
                return 1;
            }
        };

        if (\file_exists($message->getSchemaPath())) {
            $this->tester->deleteFile($message->getSchemaPath());
        }
        $this->tester->writeToFile($message->getSchemaPath(), '{}');

        $actual = $this->makeInstance();
        $this->assertIsInt($actual->process($message, $handler));
        $this->assertSame(1, $actual->process($message, $handler));

        $this->tester->deleteFile($message->getSchemaPath());
    }
}
