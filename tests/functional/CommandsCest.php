<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Functional;

use FunctionalTester;
use ItalyStrap\Tests\FunctionalTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Output\Events\NoFileFound;

class CommandsCest extends FunctionalTestCase
{
    public function testDump(FunctionalTester $i): void
    {
        $data = \codecept_data_dir('fixtures');
        $i->runShellCommand(\sprintf(
            'bin/theme-json dump --path="%s" --dry-run',
            $data
        ));
//        $i->runShellCommand('bin/theme-json dump --file="theme.json"');
//        $i->runShellCommand('bin/theme-json dump --path="tests"');
//        $i->runShellCommand('bin/theme-json dump --path="tests/_data/fixtures/themes/theme-flat/"');
        $i->dontSeeInShellOutput(NoFileFound::M_NO_FILE_FOUND);
        $i->seeResultCodeIs(0);
    }

    public function testInit(FunctionalTester $i): void
    {
        $i->runShellCommand('bin/theme-json init');
        $i->seeResultCodeIs(0);
    }

    public function testValidate(FunctionalTester $i): void
    {
        $i->runShellCommand('bin/theme-json validate');
        $i->seeResultCodeIs(0);
    }

    public function testInfo(FunctionalTester $i): void
    {
        $i->runShellCommand('bin/theme-json info');
        $i->seeResultCodeIs(0);
    }
}
