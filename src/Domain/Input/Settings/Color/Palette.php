<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color;

use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\ColorInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetTrait;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetInterface;

class Palette implements PresetInterface
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
        private readonly string $slug,
        private readonly string $name,
        private readonly ColorInterface $color
    ) {
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
