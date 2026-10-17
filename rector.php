<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PHPUnit\PHPUnit100\Rector\Class_\StaticDataProviderClassMethodRector;
use ThemeJsonGenerator\Tools\Rector\Naming\CamelCaseParameterNameRector;

require_once __DIR__ . '/tools/rector/src/Rector/Naming/CamelCaseParameterNameRector.php';

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/bin',
        __DIR__ . '/report/bin',
        __DIR__ . '/report/src',
        __DIR__ . '/functions',
        __DIR__ . '/src',
        __DIR__ . '/cli',
        __DIR__ . '/tests',
        __DIR__ . '/tools',
        __DIR__ . '/ecs.php',
        __DIR__ . '/namespace-bc-aliases.php',
        __DIR__ . '/rector.php',
    ])
    ->withSkip([
        '*Experimental*.php',
        __DIR__ . '/tests/_data',
        __DIR__ . '/tests/_output',
        __DIR__ . '/tests/_support',
    ])
    ->withRules([
        CamelCaseParameterNameRector::class,
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
