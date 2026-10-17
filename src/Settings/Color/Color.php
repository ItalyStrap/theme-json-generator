<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Values\CssColorInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetTrait;

final readonly class Color implements PresetInterface
{
    use PresetTrait;

    public const SECTION = 'palette';

    /**
     * @var string
     */
    public const TYPE = 'color';

    public function __construct(
        private string $slug,
        private string $name,
        private CssColorInterface $color
    ) {
        $this->assertSlugIsWellFormed($slug);
    }

    /**
     * @return array{slug: string, name: string, color: string}
     */
    public function toArray(): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'color' => (string)$this->color,
        ];
    }

    public function color(): CssColorInterface
    {
        return $this->color;
    }
}
