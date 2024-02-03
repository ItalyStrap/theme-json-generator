<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings;

/**
 * @infection-ignore-all
 * @psalm-api
 */
class NullPresets implements PresetsInterface
{
    public function add(PresetInterface $item): PresetsInterface
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
}
