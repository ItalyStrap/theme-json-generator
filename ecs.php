<?php

declare(strict_types=1);

use PHP_CodeSniffer\Standards\Generic\Sniffs\WhiteSpace\DisallowTabIndentSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\WhiteSpace\ScopeIndentSniff;
use PHP_CodeSniffer\Standards\PSR12\Sniffs\Classes\AnonClassDeclarationSniff;
use PHP_CodeSniffer\Standards\PSR12\Sniffs\Classes\ClassInstantiationSniff;
use PHP_CodeSniffer\Standards\PSR12\Sniffs\Classes\OpeningBraceSpaceSniff;
use PHP_CodeSniffer\Standards\PSR12\Sniffs\Operators\OperatorSpacingSniff;
use PHP_CodeSniffer\Standards\PSR2\Sniffs\Classes\ClassDeclarationSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Functions\MultiLineFunctionDeclarationSniff;
use PhpCsFixer\Fixer\ClassNotation\ClassDefinitionFixer;
use PhpCsFixer\Fixer\FunctionNotation\FunctionDeclarationFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Operator\NewWithParenthesesFixer;
use PhpCsFixer\Fixer\Operator\TernaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Whitespace\IndentationTypeFixer;
use PhpCsFixer\Fixer\Whitespace\NoExtraBlankLinesFixer;
use PhpCsFixer\Fixer\Whitespace\StatementIndentationFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/bin',
        __DIR__ . '/report/bin',
        __DIR__ . '/report/src',
        __DIR__ . '/functions',
        __DIR__ . '/src',
        __DIR__ . '/cli',
        __DIR__ . '/tests',
        __DIR__ . '/tools',
        __DIR__ . '/.phpstorm.meta.php',
        __DIR__ . '/ecs.php',
        __DIR__ . '/namespace-bc-aliases.php',
        __DIR__ . '/rector.php',
    ]);

    $ecsConfig->skip([
        BinaryOperatorSpacesFixer::class,
        ClassDefinitionFixer::class,
        ConcatSpaceFixer::class,
        FunctionDeclarationFixer::class,
        IndentationTypeFixer::class,
        NewWithParenthesesFixer::class,
        NoExtraBlankLinesFixer::class,
        StatementIndentationFixer::class,
        TernaryOperatorSpacesFixer::class,
        '*Experimental*.php',
        __DIR__ . '/tests/_data',
        __DIR__ . '/tests/_output',
        __DIR__ . '/tests/_support',
    ]);

    $ecsConfig->sets([SetList::PSR_12]);
    // Use PHPCS checks where fixer defaults impose additional formatting choices.
    $ecsConfig->rules([
        DisallowTabIndentSniff::class,
        ClassDeclarationSniff::class,
        AnonClassDeclarationSniff::class,
        ClassInstantiationSniff::class,
        OpeningBraceSpaceSniff::class,
        OperatorSpacingSniff::class,
        MultiLineFunctionDeclarationSniff::class,
    ]);
    $ecsConfig->ruleWithConfiguration(ScopeIndentSniff::class, [
        'ignoreIndentationTokens' => ['T_COMMENT', 'T_DOC_COMMENT_OPEN_TAG'],
    ]);
    $ecsConfig->ruleWithConfiguration(OrderedImportsFixer::class, [
        'imports_order' => ['class', 'function', 'const'],
        'sort_algorithm' => 'alpha',
    ]);
};
