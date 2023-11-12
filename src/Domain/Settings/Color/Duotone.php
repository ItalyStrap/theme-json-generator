<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color;

use ItalyStrap\ThemeJsonGenerator\ColorDataType;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\CanBeAddedToCollection;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities\ColorInfoInterface;

/**
 * @psalm-api
 */
class Duotone implements CanBeAddedToCollection
{
    public const KEY = 'duotone';

    private string $name;
    private string $slug;

    /**
     * @var ColorInfoInterface[] $colors
     */
    private array $colors;

    public function __construct(string $slug, string $name, ColorInfoInterface ...$colors)
    {
        $this->slug = $slug;
        $this->name = $name;
        $this->colors = $colors;
    }

    public function toArray(): array
    {
        if (empty($this->name) || empty($this->slug) || count($this->colors) < 2) {
            throw new \LogicException('DuotoneValue must have a name, slug, and at least two colors.');
        }

        $this->isValidSlug($this->slug);

        $colors = \array_map(function ($color) {
            $newColor = (string)$color;
            $this->isValidColor($newColor);
            return $newColor;
        }, $this->colors);

        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'colors' => $colors,
        ];
    }

    private function isValidColor(string $color): void
    {
        new ColorDataType($color);
    }

    private function isValidSlug(string $slug): void
    {
        if (\preg_match('/\s/', $slug) || \preg_match('/[A-Z]/', $slug)) {
            throw new \Exception('Slug must be lowercase and without spaces');
        }
    }

//    private function enforceSlugFormat(string $slug): string
//    {
//        return \trim(\mb_strtolower(\str_replace(' ', '-', $slug)));
//    }
}
