<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color;

use ItalyStrap\ThemeJsonGenerator\Domain\Settings\CanBeAddedToCollection;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities\ColorInfoInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\CommonTrait;

/**
 * @psalm-api
 */
class Palette implements CanBeAddedToCollection
{
    use CommonTrait;

    public const CATEGORY = 'palette';

    private string $slug;
    private string $name;
    private ColorInfoInterface $color;

    public function __construct(string $slug, string $name, ColorInfoInterface $color)
    {
        $this->slug = $slug;
        $this->name = $name;
        $this->color = $color;
    }

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
