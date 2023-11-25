<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color;

use ItalyStrap\ThemeJsonGenerator\Domain\Settings\ItemInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\CommonTrait;

/**
 * @psalm-api
 */
class Duotone implements ItemInterface
{
    use CommonTrait;

    public const CATEGORY = 'duotone';

    private string $name;
    private string $slug;

    /**
     * @var Palette [] $colors
     */
    private array $colors;

    public function __construct(string $slug, string $name, Palette ...$colors)
    {
        $this->isValidSlug($slug);

        if (empty($name) || empty($slug) || count($colors) < 2) {
            throw new \LogicException('Duotone must have a name, slug, and at least two colors.');
        }

        $this->slug = $slug;
        $this->name = $name;
        $this->colors = $this->assertValidColors(...$colors);
    }

    public function toArray(): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'colors' => $this->colors,
        ];
    }

    /**
     * @return string[]
     */
    private function assertValidColors(Palette ...$colors): array
    {
        return \array_map(fn(Palette $color) => (string)$color->color()->toRgba(), $colors);
    }
}
