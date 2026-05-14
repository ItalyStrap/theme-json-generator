<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Application\Middlewares;

use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Application\Middlewares\SchemaJson;

final class SchemaJsonTest extends UnitTestCase
{
    private function makeInstance(): SchemaJson
    {
        return new SchemaJson();
    }

    public function testProcess()
    {
        $message = new class {
            public function getSchemaPath(): string
            {
                return \codecept_output_dir('theme.schema.json');
            }
        };

        $handler = new class implements HandlerInterface {
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
