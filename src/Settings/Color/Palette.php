<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\ColorInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetTrait;

final readonly class Palette implements PresetInterface
{
    use PresetTrait;

    /**
     * @var string
     */
    public const TYPE = 'color';

    /**
     * @var string
     */
    public const KEY = 'settings.color.palette';

    public function __construct(
        private string $slug,
        private string $name,
        private ColorInterface $color
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

    public function color(): ColorInterface
    {
        return $this->color;
    }
}
