<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\ThemeJsonGenerator\Application\Commands\Composer;

use Composer\Console\Application;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer\ThemeJson;
use Prophecy\Argument;
use Symfony\Component\Console\Tester\CommandTester;

class ThemeJsonTest extends UnitTestCase
{
    protected function makeInstance(): ThemeJson
    {
        return new ThemeJson();
    }

    public function testItShouldBeInstantiatable(): void
    {
        $sut = $this->makeInstance();
    }

    public function testItShouldRun(): void
    {
        $sut = $this->makeInstance();

//      $application = new Application();
//      $application->setAutoExit(false);
//      $application->add($sut);
//      $application->run();

//      $tester = new CommandTester($sut);
//      $tester->setInputs([]);
//      $tester->execute([]);

//      $tester->assertCommandIsSuccessful();
    }
}
