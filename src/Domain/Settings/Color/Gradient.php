<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color;

class Gradient
{
    public const KEY = 'gradient';

    private string $slug;
    private string $gradient;
    private string $name;

    public function __construct(string $slug, string $name, string $gradient)
    {
        $this->slug = $slug;
        $this->name = $name;
        $this->gradient = $gradient;
    }

    public function toArray(): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'gradient' => $this->gradient,
        ];
    }
}
