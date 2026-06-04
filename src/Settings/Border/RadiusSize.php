<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Border;

use ItalyStrap\ThemeJsonGenerator\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetTrait;

final readonly class RadiusSize implements PresetInterface
{
    use PresetTrait;

    public const TYPE = 'borderRadius';

    public function __construct(
        private string $slug,
        private string $name,
        private string $size,
    ) {
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
