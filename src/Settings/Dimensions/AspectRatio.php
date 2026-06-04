<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Dimensions;

use ItalyStrap\ThemeJsonGenerator\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetTrait;

final readonly class AspectRatio implements PresetInterface
{
    use PresetTrait;

    public const TYPE = 'aspectRatio';

    public function __construct(
        private string $slug,
        private string $name,
        private string $ratio,
    ) {
    }

    /**
     * @return array{slug: string, name: string, ratio: string}
     */
    public function toArray(): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'ratio' => $this->ratio,
        ];
    }
}
