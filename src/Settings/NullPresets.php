<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

/**
 * @infection-ignore-all
 */
final class NullPresets implements PresetsInterface
{
    public function add(PresetInterface $item): PresetsInterface
    {
        return $this;
    }

    public function addAt(array|string $path, PresetInterface $item): PresetsInterface
    {
        return $this;
    }

    public function addMultiple(array $items): PresetsInterface
    {
        return $this;
    }

    public function get(string $key, $default = null)
    {
        return $default;
    }

    public function parse(string $content): string
    {
        return $content;
    }

    public function toArrayByCategory(string $category): array
    {
        return [];
    }

    public function toArraysByPath(): array
    {
        return [];
    }
}
