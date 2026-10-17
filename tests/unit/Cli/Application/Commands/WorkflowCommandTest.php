<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Cli\Application\Commands;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Commands\WorkflowCommand;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Message;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Handler\ConsoleHandler;
use Prophecy\Argument;
use Symfony\Component\Console\Tester\CommandTester;

final class WorkflowCommandTest extends UnitTestCase
{
    public function testItDispatchesTheExplicitPathAndReturnsTheHandlerExitCode(): void
    {
        $this->assertDispatch(['--path' => '/theme/selected'], '/theme/selected');
    }

    public function testItDispatchesTheWorkingDirectoryByDefault(): void
    {
        $this->assertDispatch([], (string) \getcwd());
    }

    /** @param array<string, string> $arguments */
    private function assertDispatch(array $arguments, string $expectedPath): void
    {
        $handler = $this->prophesize(ConsoleHandler::class);
        $handler->handle(Argument::that(
            static fn (object $message): bool => $message instanceof Message
                && $message->getRootFolder() === $expectedPath
        ))->willReturn(7)->shouldBeCalledOnce();

        $tester = new CommandTester(new WorkflowCommand($handler->reveal()));

        $this->assertSame(7, $tester->execute($arguments));
    }
}
