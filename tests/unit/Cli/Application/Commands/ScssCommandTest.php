<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Cli\Application\Commands;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Commands\ScssCommand;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\ScssMessage;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Handler\ConsoleHandler;
use Prophecy\Argument;
use Symfony\Component\Console\Tester\CommandTester;

final class ScssCommandTest extends UnitTestCase
{
    public function testItDispatchesToConsoleHandlerAndPreservesItsExitCode(): void
    {
        $handler = $this->prophesize(ConsoleHandler::class);
        $handler->handle(Argument::that(
            static fn (object $message): bool => $message instanceof ScssMessage
                && $message->input === 'styles/dark.json'
                && $message->output === 'assets/_dark.scss'
                && $message->check
                && !$message->dryRun
        ))->willReturn(7)->shouldBeCalledOnce();

        $tester = new CommandTester(new ScssCommand($handler->reveal()));
        $this->assertSame(7, $tester->execute([
            '--input' => 'styles/dark.json', '--output' => 'assets/_dark.scss', '--check' => true,
        ]));
    }
}
