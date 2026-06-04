<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Functional;

use FunctionalTester;
use ItalyStrap\Tests\FunctionalTestCase;

final class ThemeJsonFacadesCest extends FunctionalTestCase
{
    public function testRootFacades(FunctionalTester $i): void
    {
        $topLevelOutput = '"$schema":"https:\/\/schemas.wp.org\/trunk\/theme.json","version":3,'
            . '"title":"Moduli","slug":"moduli","description":"Theme metadata"';
        $settingsOutput = '"settings":{"background":{"backgroundImage":"url(hero.jpg)"},'
            . '"layout":{"contentSize":"960px"}';

        $i->runShellCommand(\sprintf(
            'php %s',
            \escapeshellarg((string) \codecept_data_dir('fixtures/theme-json-root-facades.php'))
        ));

        $i->seeResultCodeIs(0);
//        $i->seeInShellOutput($topLevelOutput);
//        $i->seeInShellOutput($settingsOutput);
        $i->seeInShellOutput('"custom":{"brand":{"primary":"#111111"}}');
//        $i->seeInShellOutput('"styles":{"background":{"backgroundImage":"url(hero.jpg)"}');
        $i->seeInShellOutput('"variations":{"outline":{"color":{"text":"#333333"}}}');
    }
}
