<?php

declare(strict_types=1);

namespace ThemeJsonGenerator\Tools\Rector\Naming;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Identifier;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class CamelCaseParameterNameRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename snake_case parameter names to camelCase.',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
function run(string $file_name): void
{
    echo $file_name;
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
function run(string $fileName): void
{
    echo $fileName;
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [
            Class_::class,
            ClassMethod::class,
            Closure::class,
            Function_::class,
        ];
    }

    /**
     * @param Class_|ClassMethod|Closure|Function_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Class_) {
            return $this->refactorClass($node);
        }

        return $this->refactorFunctionLike($node);
    }

    private function refactorClass(Class_ $class): ?Class_
    {
        $propertyRenames = [];
        foreach ($class->getMethods() as $method) {
            foreach ($method->params as $param) {
                $oldName = $this->parameterName($param);
                $newName = $this->camelCase($oldName);

                if ($oldName === $newName) {
                    continue;
                }

                if ($param->flags !== 0) {
                    $propertyRenames[$oldName] = $newName;
                }
            }
        }

        if ($propertyRenames === []) {
            return null;
        }

        $this->renamePropertyFetches($class, $propertyRenames);

        return $class;
    }

    private function refactorFunctionLike(FunctionLike $functionLike): ?Node
    {
        $variableRenames = [];
        foreach ($functionLike->getParams() as $param) {
            if (!$param->var instanceof Variable) {
                continue;
            }

            $oldName = $this->parameterName($param);
            $newName = $this->camelCase($oldName);

            if ($oldName === $newName) {
                continue;
            }

            $param->var->name = $newName;
            $variableRenames[$oldName] = $newName;
        }

        if ($variableRenames === []) {
            return null;
        }

        $this->renameVariables($functionLike, $variableRenames);

        return $functionLike;
    }

    /**
     * @param array<string, string> $renames
     */
    private function renameVariables(FunctionLike $functionLike, array $renames): void
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new class ($renames, $functionLike) extends NodeVisitorAbstract {
            /**
             * @param array<string, string> $renames
             */
            public function __construct(
                private readonly array $renames,
                private readonly FunctionLike $scope
            ) {
            }

            public function enterNode(Node $node): ?int
            {
                if ($node instanceof FunctionLike && $node !== $this->scope) {
                    return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                if (!$node instanceof Variable || !\is_string($node->name)) {
                    return null;
                }

                if (!\array_key_exists($node->name, $this->renames)) {
                    return null;
                }

                $node->name = $this->renames[$node->name];

                return null;
            }
        });

        $traverser->traverse($this->functionLikeNodes($functionLike));
    }

    /**
     * @param array<string, string> $renames
     */
    private function renamePropertyFetches(Class_ $class, array $renames): void
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new class ($renames) extends NodeVisitorAbstract {
            /**
             * @param array<string, string> $renames
             */
            public function __construct(private readonly array $renames)
            {
            }

            public function enterNode(Node $node): ?int
            {
                if (!$node instanceof PropertyFetch) {
                    return null;
                }

                if (!$node->var instanceof Variable || $node->var->name !== 'this') {
                    return null;
                }

                if (!$node->name instanceof Identifier) {
                    return null;
                }

                $name = $node->name->toString();
                if (!\array_key_exists($name, $this->renames)) {
                    return null;
                }

                $node->name = new Identifier($this->renames[$name]);

                return null;
            }
        });

        $traverser->traverse($class->stmts);
    }

    /**
     * @return list<Node>
     */
    private function functionLikeNodes(FunctionLike $functionLike): array
    {
        if ($functionLike instanceof Closure) {
            return \array_values($functionLike->stmts);
        }

        if ($functionLike instanceof Function_ || $functionLike instanceof ClassMethod) {
            return \array_values($functionLike->stmts ?? []);
        }

        return [];
    }

    private function parameterName(Param $param): string
    {
        if (!$param->var instanceof Variable || !\is_string($param->var->name)) {
            return '';
        }

        return $param->var->name;
    }

    private function camelCase(string $name): string
    {
        return (string) \preg_replace_callback(
            '/_([a-zA-Z0-9])/',
            static fn (array $matches): string => \strtoupper($matches[1]),
            $name
        );
    }
}
