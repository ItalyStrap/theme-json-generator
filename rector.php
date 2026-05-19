<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PHPUnit\PHPUnit100\Rector\Class_\StaticDataProviderClassMethodRector;
use Rector\Set\ValueObject\SetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/bin',
        __DIR__ . '/functions',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withSkip([
        __DIR__ . '/tests/_data',
        __DIR__ . '/tests/_output',
        __DIR__ . '/tests/_support/_generated',
        __DIR__ . '/tests/integration/Domain/Input/Styles/ProcessBlocksCustomCssTrait.php',
    ])
    ->withRules([
        StaticDataProviderClassMethodRector::class,
    ])
    ->withPreparedSets(
//        deadCode: true, // Not yet, this removes also some commented code that I need to keep.
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        instanceOf: true,
        earlyReturn: true,
//        carbon: true,
        phpunitCodeQuality: true,
    )
    ->withImportNames(
        importDocBlockNames: false,
        importShortClasses: false,
        removeUnusedImports: true,
    )
    ->withPhpSets()
    ->withoutParallel();
