<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Tools;

use ItalyStrap\Tests\UnitTestCase;
use Symfony\Component\Process\Process;

final class CodingStandardsParityTest extends UnitTestCase
{
    /**
     * @dataProvider styleProvider
     */
    public function testBothToolsShouldAgreeOnStyle(string $content, bool $valid): void
    {
        $fixture = \sys_get_temp_dir() . '/coding-standards-parity-' . \bin2hex(\random_bytes(6)) . '.php';
        $this->assertNotFalse(\file_put_contents($fixture, $content));

        try {
            $phpcs = new Process([
                \PHP_BINARY,
                \codecept_root_dir('vendor/bin/phpcs'),
                '--standard=' . \codecept_root_dir('phpcs.xml'),
                '--report=json',
                $fixture,
            ], \codecept_root_dir());
            $ecs = new Process([
                \PHP_BINARY,
                \codecept_root_dir('vendor/bin/ecs'),
                'check',
                '--config=' . \codecept_root_dir('ecs.php'),
                '--no-progress-bar',
                '--output-format=json',
                $fixture,
            ], \codecept_root_dir());

            $this->assertSame($valid, $phpcs->run() === 0, $phpcs->getOutput() . $phpcs->getErrorOutput());
            $this->assertSame($valid, $ecs->run() === 0, $ecs->getOutput() . $ecs->getErrorOutput());
        } finally {
            \unlink($fixture);
        }
    }

    /**
     * @return iterable<string, array{string, bool}>
     */
    public static function styleProvider(): iterable
    {
        yield 'accepted formatting choices' => [<<<'PHP'
<?php

namespace CodingStandardsParity;

use LogicException;
use RuntimeException;

final class FlexibleStyle
{
    public function build(): array
    {

// A comment may be unindented.
        $number =  1;
        $message = 'first'  .  'second';
        $compact = static fn(int $value): int => $value;
        $spaced = static fn (int $value): int => $value;

        return [
            new class {
            },
            new class () {
            },
            new class ($number) {
                public function __construct(private int $number)
                {
                }
            },
            new RuntimeException(),
            $compact($number),
            $spaced($number),
            $message,
        ];
    }
}
PHP . "\n", true];

        yield 'unsorted imports' => [<<<'PHP'
<?php

namespace CodingStandardsParity;

use RuntimeException;
use LogicException;

final class UnsortedImports
{
}
PHP . "\n", false];

        yield 'missing constructor parentheses' => [<<<'PHP'
<?php

namespace CodingStandardsParity;

function build(): object
{
    return new \stdClass;
}
PHP . "\n", false];

        yield 'missing anonymous-class spacing' => [<<<'PHP'
<?php

namespace CodingStandardsParity;

function build(): object
{
    return new class() {
    };
}
PHP . "\n", false];

        yield 'incorrect indentation' => [<<<'PHP'
<?php

namespace CodingStandardsParity;

function build(): int
{
  return 1;
}
PHP . "\n", false];

        yield 'missing operator spacing' => [<<<'PHP'
<?php

namespace CodingStandardsParity;

function build(): int
{
    $number=1;
    return $number;
}
PHP . "\n", false];
    }
}
