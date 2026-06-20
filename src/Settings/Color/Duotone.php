<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color;

use ItalyStrap\ThemeJsonGenerator\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetTrait;

final class Duotone implements PresetInterface
{
    use PresetTrait;

    /**
     * @var string
     */
    public const TYPE = 'duotone';

    private string $name;

    /**
     * @var array<array-key, string> $colors
     */
    private array $colors = [];

    public function __construct(
        private readonly string $slug,
        string $name,
        Palette ...$colors
    ) {
        $this->assertSlugIsWellFormed($slug);

        if ($name === '') {
            throw new \InvalidArgumentException('Duotone must have a name.');
        }

        if (count($colors) < 2) {
            throw new \InvalidArgumentException('Duotone must have at least two colors.');
        }

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
        return \array_map(static fn (Palette $color): string => (string)$color->color()->toRgba(), $colors);
    }
}
