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

    public function addToBlock(string $block, PresetInterface $item): PresetsInterface
    {
        return $this;
    }

    public function addMultiple(array $items): PresetsInterface
    {
        return $this;
    }

    public function get(array|string $key, $default = null)
    {
        return $default;
    }

    /**
     * Keep placeholder tokens unresolved because this implementation represents
     * disabled preset resolution.
     */
    public function parse(string $content): string
    {
        return $content;
    }

    public function presets(): iterable
    {
        return [];
    }

    public function collection(): array
    {
        return [];
    }
}
