<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Cli\Application\Middlewares;

use ItalyStrap\Pipeline\CallbackHandler;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\DumpMessage;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Middlewares\Dump;
use ItalyStrap\ThemeJsonGenerator\Cli\Infrastructure\Container\ThemeJsonContainerFactory;
use Prophecy\Argument;

final class DumpTest extends UnitTestCase
{
    private function makeInstance(): Dump
    {
        return new Dump(
            $this->makeFilesFinder(),
            new ThemeJsonContainerFactory(),
        );
    }

    private function makeHandler(): CallbackHandler
    {
        return new CallbackHandler(static fn (object $message): int => 0);
    }

    public function testItShouldHandleButDoNothing(): void
    {
        $this->filesFinder
            ->find(Argument::type('string'), Argument::exact('php'))
            ->willReturn([])
            ->shouldBeCalledOnce();

        $this->makeInstance()->process(new DumpMessage('', '', false, ''), $this->makeHandler());
    }

    public function testItShouldBasicExample(): void
    {
        $basicExample = new \SplFileInfo(\codecept_data_dir('fixtures/basic-example.json.php'));
        $this->filesFinder
            ->find(Argument::type('string'), Argument::exact('php'))
            ->willReturn([
                $basicExample->getBasename('.json.php') => $basicExample,
            ]);

        $this->filesFinder
            ->resolveJsonFile($basicExample)
            ->willReturn(\codecept_data_dir('fixtures/basic-example.json'));

        $this->makeInstance()->process(new DumpMessage('', '', false, ''), $this->makeHandler());

        $generatedFile = new \SplFileInfo(\codecept_data_dir('fixtures/basic-example.json'));
        $this->assertFileExists($generatedFile->getPathname(), 'The file was not generated');

        \unlink($generatedFile->getPathname());
    }

    public function testItShouldAdvancedExample(): void
    {
        $advancedExample = new \SplFileInfo(\codecept_data_dir('fixtures/advanced-example.json.php'));
        $this->filesFinder
            ->find(Argument::type('string'), Argument::exact('php'))
            ->willReturn([
                $advancedExample->getBasename('.json.php') => $advancedExample,
            ]);

        $this->filesFinder
            ->resolveJsonFile($advancedExample)
            ->willReturn(\codecept_data_dir('fixtures/advanced-example.json'));

        $this->makeInstance()->process(new DumpMessage('', '', false, ''), $this->makeHandler());

        $generatedFile = new \SplFileInfo(\codecept_data_dir('fixtures/advanced-example.json'));
        $this->assertFileExists($generatedFile->getPathname(), 'The file was not generated');

        \unlink($generatedFile->getPathname());
    }
}
