<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\Whitespace\StatementIndentationFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/src',
		__DIR__ . '/tests',
		__DIR__ . '/functions',
		__DIR__ . '/bin',
    ]);

	$ecsConfig->skip([
		StatementIndentationFixer::class,
		__DIR__ . '/tests/_data',
		__DIR__ . '/tests/_output',
		__DIR__ . '/tests/_support/_generated',
	]);

    // this way you add a single rule
    $ecsConfig->rules([
//        NoUnusedImportsFixer::class,
		DeclareStrictTypesFixer::class
    ]);

    // this way you can add sets - group of rules
    $ecsConfig->sets([
        // run and fix, one by one
        // SetList::SPACES,
        // SetList::ARRAY,
        // SetList::DOCBLOCK,
        // SetList::NAMESPACES,
        // SetList::COMMENTS,
         SetList::PSR_12,
    ]);
};
