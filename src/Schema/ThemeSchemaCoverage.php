<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Schema;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final readonly class ThemeSchemaCoverage
{
    /**
     * @param non-empty-string|non-empty-list<non-empty-string> $path
     */
    public function __construct(
        public string|array $path,
    ) {
    }
}
