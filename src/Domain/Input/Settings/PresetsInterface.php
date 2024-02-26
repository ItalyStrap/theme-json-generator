<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings;

/**
 * @psalm-api
 */
interface PresetsInterface
{
    public function add(PresetInterface $item): self;

    /**
     * @param PresetInterface[] $items
     */
    public function addMultiple(array $items): self;

    /**
     * @param mixed $default
     * @return array<string, mixed>|PresetInterface|mixed|null
     */
    public function get(string $key, $default = null);

    public function parse(string $content): string;
}
