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

    /**
     * @var string
     */
    public const CATEGORY = 'duotone';

    private string $name;

    private string $slug;

    /**
     * @var array<array-key, string> $colors
     */
    private array $colors = [];

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

    /**
     * @return array{slug: string, name: string, colors: string[]}
     */
    public function toArray(): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'colors' => $this->colors,
        ];
    }

    /**
     * @return array<array-key, string>
     */
    private function assertValidColors(Palette ...$colors): array
    {
        return \array_map(static fn(Palette $color): string => (string)$color->color()->toRgba(), $colors);
    }
}
