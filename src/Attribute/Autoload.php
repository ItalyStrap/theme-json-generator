<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Attribute;

#[\Attribute(\Attribute::TARGET_FUNCTION | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final readonly class Autoload
{
    public function __construct(public string $path)
    {
    }
}
