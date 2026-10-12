<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Report;

interface ThemeSchemaApiCoverageProvider
{
    /**
     * @return array<string, array{
     *     supported_by: list<array{class-string, string|null}>
     * }>
     */
    public function all(): array;
}
