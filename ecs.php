<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Basic\BracesFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassDefinitionFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\Whitespace\StatementIndentationFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/bus',
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/functions',
        __DIR__ . '/bin',
        __DIR__ . '/ecs.php',
    ]);

    $ecsConfig->skip([
        StatementIndentationFixer::class,
        BracesFixer::class,
        ClassDefinitionFixer::class,
        __DIR__ . '/tests/_data',
        __DIR__ . '/tests/_output',
        __DIR__ . '/tests/_support/_generated',
    ]);

    // this way you add a single rule
    $ecsConfig->rules([
        NoUnusedImportsFixer::class,
        DeclareStrictTypesFixer::class
    ]);

    // this way you can add sets - group of rules
    $ecsConfig->sets([
        // run and fix, one by one
        SetList::COMMON,
        SetList::PHPUNIT,
        SetList::CLEAN_CODE,
        SetList::ARRAY,
        SetList::COMMENTS,
        SetList::CONTROL_STRUCTURES,
        SetList::DOCBLOCK,
        SetList::NAMESPACES,
        SetList::SPACES,
        SetList::SYMPLIFY,
        SetList::PSR_12,
    ]);
};
