<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Cli\Application\Middlewares;

use ItalyStrap\Pipeline\CallbackHandler;
use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Middlewares\SchemaJson;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\ValidateMessage;

final class SchemaJsonTest extends UnitTestCase
{
    public function testItShouldRejectNonIntegerHandlerResults(): void
    {
        $schemaPath = \codecept_output_dir('invalid-handler-schema.json');
        $this->tester->writeToFile($schemaPath, '{}');
        $handler = new CallbackHandler(static fn (object $message): string => 'invalid');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Expected middleware handler to return an integer exit code.');

        try {
            $this->makeInstance()->process(new ValidateMessage('', $schemaPath), $handler);
        } finally {
            $this->tester->deleteFile($schemaPath);
        }
    }

    private function makeInstance(): SchemaJson
    {
        return new SchemaJson();
    }

    public function testProcess(): void
    {
        $schemaPath = \codecept_output_dir('theme.schema.json');
        $message = new ValidateMessage('', $schemaPath);

        $handler = new class implements HandlerInterface {
            public function handle(object $message): int
            {
                return 1;
            }
        };

        if (\file_exists($schemaPath)) {
            $this->tester->deleteFile($schemaPath);
        }

        $this->tester->writeToFile($schemaPath, '{}');

        $actual = $this->makeInstance();
        $this->assertSame(1, $actual->process($message, $handler));

        $this->tester->deleteFile($schemaPath);
    }
}
