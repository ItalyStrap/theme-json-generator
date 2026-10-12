<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Typography;

use ItalyStrap\ThemeJsonGenerator\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetTrait;

final readonly class TextShadow implements PresetInterface
{
    use PresetTrait;

    public const SECTION = 'textShadowPresets';

    public const TYPE = 'textShadow';

    public function __construct(
        private string $slug,
        private string $name,
        private string $textShadow
    ) {
        $this->assertSlugIsWellFormed($slug);
    }

    /**
     * @return array{slug: string, name: string, textShadow: string}
     */
    public function toArray(): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'textShadow' => $this->textShadow,
        ];
    }
}
