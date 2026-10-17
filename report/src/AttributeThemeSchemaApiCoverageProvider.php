<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Report;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;

final readonly class AttributeThemeSchemaApiCoverageProvider implements ThemeSchemaApiCoverageProvider
{
    public function __construct(
        private string $sourceDirectory = __DIR__ . '/../../src',
        private string $rootNamespace = 'ItalyStrap\\ThemeJsonGenerator',
    ) {
    }

    /**
     * @return array<string, array{
     *     supported_by: list<array{class-string, string|null}>
     * }>
     */
    public function all(): array
    {
        $coverage = [];

        foreach ($this->classNames() as $className) {
            if (!\class_exists($className)) {
                continue;
            }

            /** @var class-string $className */
            $reflectionClass = new \ReflectionClass($className);
            $this->collectClassCoverage($coverage, $reflectionClass);

            foreach ($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->getDeclaringClass()->getName() !== $className) {
                    continue;
                }

                $this->collectMethodCoverage($coverage, $className, $method);
            }
        }

        return $coverage;
    }

    /**
     * @param array<string, array{
     *     supported_by: list<array{class-string, string|null}>
     * }> $coverage
     * @param \ReflectionClass<object> $class
     */
    private function collectClassCoverage(array &$coverage, \ReflectionClass $class): void
    {
        foreach ($class->getAttributes(ThemeSchemaCoverage::class) as $reflectionAttribute) {
            $this->collectCoverage(
                $coverage,
                $reflectionAttribute->newInstance(),
                $class->getName(),
                null
            );
        }
    }

    /**
     * @param array<string, array{
     *     supported_by: list<array{class-string, string|null}>
     * }> $coverage
     * @param class-string $className
     */
    private function collectMethodCoverage(array &$coverage, string $className, \ReflectionMethod $method): void
    {
        foreach ($method->getAttributes(ThemeSchemaCoverage::class) as $reflectionAttribute) {
            $this->collectCoverage(
                $coverage,
                $reflectionAttribute->newInstance(),
                $className,
                $method->getName()
            );
        }
    }

    /**
     * @param array<string, array{
     *     supported_by: list<array{class-string, string|null}>
     * }> $coverage
     * @param class-string $className
     */
    private function collectCoverage(
        array &$coverage,
        ThemeSchemaCoverage $attribute,
        string $className,
        ?string $methodName,
    ): void {
        $path = $this->normalizePath($attribute->path);

        $coverage[$path] ??= [
            'supported_by' => [],
        ];

        $coverage[$path]['supported_by'][] = [$className, $methodName];
    }

    /**
     * @return list<string>
     */
    private function classNames(): array
    {
        $classes = [];
        $sourceDirectory = \realpath($this->sourceDirectory);

        if ($sourceDirectory === false) {
            return [];
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourceDirectory, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if (!$file instanceof \SplFileInfo) {
                continue;
            }

            if ($file->getExtension() !== 'php') {
                continue;
            }

            $relativePath = \substr($file->getPathname(), \strlen($sourceDirectory) + 1);
            $classes[] = $this->rootNamespace . '\\' . \str_replace(
                '/',
                '\\',
                \substr($relativePath, 0, -4)
            );
        }

        return $classes;
    }

    /**
     * @param non-empty-string|non-empty-list<non-empty-string> $path
     */
    private function normalizePath(string|array $path): string
    {
        if (\is_string($path)) {
            return $path;
        }

        return \implode('.', $path);
    }
}
