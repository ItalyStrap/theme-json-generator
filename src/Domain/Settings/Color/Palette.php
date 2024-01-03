<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color;

use ItalyStrap\ThemeJsonGenerator\Domain\Settings\ItemInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities\ColorInfoInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\CommonTrait;

/**
 * @psalm-api
 */
class Palette implements ItemInterface
{
    use CommonTrait;

    /**
     * @var string
     */
    public const CATEGORY = 'color';

    /**
     * @var string
     */
    public const KEY = 'settings.color.palette';

    private string $slug;

    private string $name;

    private ColorInfoInterface $color;

    public function __construct(string $slug, string $name, ColorInfoInterface $color)
    {
        $this->slug = $slug;
        $this->name = $name;
        $this->color = $color;
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

    public function color(): ColorInfoInterface
    {
        return $this->color;
    }
}
