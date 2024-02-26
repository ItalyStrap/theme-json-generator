<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Functional;

use FunctionalTester;
use ItalyStrap\Tests\FunctionalTestCase;

class DumpCest extends FunctionalTestCase
{
    public function testTryToTest(FunctionalTester $i): void
    {
        $i->runShellCommand('bin/theme-json dump');
//        $i->seeInShellOutput('Generating theme.json file');
//        $i->seeInShellOutput('Generated theme.json file');
        $i->seeResultCodeIs(0);
    }
}
