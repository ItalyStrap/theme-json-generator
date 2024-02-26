<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Output;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\DumpMessage;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Dump;
use Prophecy\Argument;

class DumpTest extends UnitTestCase
{
    private function makeInstance(): Dump
    {
        return new Dump(
            $this->makeDispatcher(),
            //            $this->makeConfig(),
            new Config(),
            $this->makeFilesFinder(),
        );
    }

    public function testItShouldHandleButDoNothing(): void
    {
        $this->filesFinder
            ->find(Argument::type('string'), Argument::exact('php'))
            ->willReturn([])
            ->shouldBeCalledOnce();

        $this->makeInstance()->handle(new DumpMessage('', '', false));
    }

    public function testItShouldBasicExample(): void
    {
        $basicExample = new \SplFileInfo(\codecept_data_dir('fixtures/basic-example.json.php'));
        $this->filesFinder
            ->find(Argument::type('string'), Argument::exact('php'))
            ->willReturn([
                $basicExample->getBasename('.json.php') => $basicExample,
            ]);

        $this->makeInstance()->handle(new DumpMessage('', '', false));

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

        $this->makeInstance()->handle(new DumpMessage('', '', false));

        $generatedFile = new \SplFileInfo(\codecept_data_dir('fixtures/advanced-example.json'));
        $this->assertFileExists($generatedFile->getPathname(), 'The file was not generated');

        \unlink($generatedFile->getPathname());
    }
}
