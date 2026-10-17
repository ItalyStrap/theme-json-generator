<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Spacing;

use ItalyStrap\ThemeJsonGenerator\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetTrait;

final readonly class SpacingSize implements PresetInterface
{
    use PresetTrait;

    public const SECTION = 'spacingSizes';

    public const TYPE = 'spacing';

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
