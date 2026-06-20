<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Dimensions;

use ItalyStrap\ThemeJsonGenerator\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetTrait;

final readonly class DimensionSize implements PresetInterface
{
    use PresetTrait;

    public const TYPE = 'dimension';

    public function __construct(
        private string $slug,
        private string $name,
        private string $size,
    ) {
        $this->assertSlugIsWellFormed($slug);
    }

    /**
     * @return array{slug: string, name: string, size: string}
     */
    public function toArray(): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'size' => $this->size,
        ];
    }
}
