<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Attribute;

#[\Attribute(\Attribute::TARGET_FUNCTION | \Attribute::TARGET_METHOD)]
final readonly class ScssOutput
{
    public function __construct(public string $path, public ?string $filename = null)
    {
    }
}
