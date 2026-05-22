<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Cli\Application\Middlewares;

use ItalyStrap\Pipeline\HandlerInterface;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Middlewares\SchemaJson;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\ValidateMessage;

final class SchemaJsonTest extends UnitTestCase
{
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
        $this->assertIsInt($actual->process($message, $handler));
        $this->assertSame(1, $actual->process($message, $handler));

        $this->tester->deleteFile($schemaPath);
    }
}
