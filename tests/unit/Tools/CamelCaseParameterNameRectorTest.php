<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Tools;

use ItalyStrap\Tests\UnitTestCase;
use Symfony\Component\Process\Process;

final class CamelCaseParameterNameRectorTest extends UnitTestCase
{
    public function testItShouldRenameParametersAndSparseStatementNodes(): void
    {
        $result = $this->runRule(<<<'PHP'
$parameter = new PhpParser\Node\Expr\Variable('file_name');
$reference = new PhpParser\Node\Expr\Variable('file_name');
$method = new PhpParser\Node\Stmt\ClassMethod('run', [
    'params' => [new PhpParser\Node\Param($parameter)],
    'stmts' => [7 => new PhpParser\Node\Stmt\Expression($reference)],
]);
$rule = new ThemeJsonGenerator\Tools\Rector\Naming\CamelCaseParameterNameRector();
echo json_encode([
    'sameNode' => $rule->refactor($method) === $method,
    'parameter' => $parameter->name,
    'reference' => $reference->name,
], JSON_THROW_ON_ERROR);
PHP);

        $this->assertSame([
            'sameNode' => true,
            'parameter' => 'fileName',
            'reference' => 'fileName',
        ], $result);
    }

    public function testItShouldLeaveInvalidParameterNodesUntouched(): void
    {
        $result = $this->runRule(<<<'PHP'
$parameter = new PhpParser\Node\Param(new PhpParser\Node\Expr\Error());
$method = new PhpParser\Node\Stmt\ClassMethod('run', ['params' => [$parameter]]);
$rule = new ThemeJsonGenerator\Tools\Rector\Naming\CamelCaseParameterNameRector();
echo json_encode($rule->refactor($method), JSON_THROW_ON_ERROR);
PHP);

        $this->assertNull($result);
    }

    private function runRule(string $script): mixed
    {
        // Rector and Codeception bundle different parser versions; use Rector's CLI bootstrap order.
        $bootstrap = <<<'PHP'
require 'vendor/rector/rector/preload.php';
require 'vendor/rector/rector/vendor/scoper-autoload.php';
require 'vendor/autoload.php';
require 'tools/rector/src/Rector/Naming/CamelCaseParameterNameRector.php';
PHP;
        $process = new Process([\PHP_BINARY, '-r', $bootstrap . "\n" . $script], \codecept_root_dir());
        $this->assertSame(0, $process->run(), $process->getOutput() . $process->getErrorOutput());

        return \json_decode($process->getOutput(), true, 512, \JSON_THROW_ON_ERROR);
    }
}
