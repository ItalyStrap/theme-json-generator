<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

interface PresetsInterface
{
    public function add(PresetInterface $item): self;

    public function addToBlock(string $block, PresetInterface $item): self;

    /**
     * @param PresetInterface[] $items
     */
    public function addMultiple(array $items): self;

    /**
     * @param array<array-key, string|int>|string $key
     * @param mixed $default
     * @return array<string, mixed>|PresetInterface|mixed|null
     */
    public function get(array|string $key, $default = null);

    public function parse(string $content): string;

    /**
     * @return array<array-key, mixed>
     */
    public function collection(): array;
}
