<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PHPUnit\PHPUnit100\Rector\Class_\StaticDataProviderClassMethodRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Strict\Rector\Ternary\DisallowedShortTernaryRuleFixerRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/bin',
        __DIR__ . '/functions',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $rectorConfig->skip([
        DisallowedShortTernaryRuleFixerRector::class,
        __DIR__ . '/tests/_data',
        __DIR__ . '/tests/_output',
        __DIR__ . '/tests/_support/_generated',
        __DIR__ . '/tests/integration/Domain/Input/Styles/ProcessBlocksCustomCssTrait.php',
    ]);

    // register a single rule
    $rectorConfig->rule(StaticDataProviderClassMethodRector::class);

    // define sets of rules
    $rectorConfig->sets([
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
//        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::TYPE_DECLARATION,
        SetList::STRICT_BOOLEANS,
        SetList::INSTANCEOF,
        LevelSetList::UP_TO_PHP_74
    ]);
};
