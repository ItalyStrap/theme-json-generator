<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Functional;

use FunctionalTester;
use ItalyStrap\Tests\FunctionalTestCase;
use ItalyStrap\ThemeJsonGenerator\Cli\Application\Middlewares\Dump;

final class CommandsCest extends FunctionalTestCase
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
        $i->dontSeeInShellOutput(Dump::M_NO_FILE_FOUND);
        $i->seeResultCodeIs(0);
    }

    public function testDumpDisplaysExceptionLocation(FunctionalTester $i): void
    {
        $data = \codecept_data_dir('fixtures-invalid-style-chain');
        $i->runShellCommand(\sprintf(
            'bin/theme-json dump --path="%s" --dry-run 2>&1',
            $data
        ), false);

        $i->seeResultCodeIs(1);
        $i->seeInShellOutput('In StyleContext.php line');
        $i->seeInShellOutput('Cannot chain "elements()" after "elements()"');
        $i->seeShellOutputMatches('/fixtures-invalid-style-chain\/theme\.jso\s+n\.php:8/');
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
