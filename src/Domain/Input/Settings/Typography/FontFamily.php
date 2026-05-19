<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Typography;

use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetTrait;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetInterface;

class FontFamily implements PresetInterface
{
    use PresetTrait;

    /**
     * @var string
     */
    public const TYPE = 'fontFamily';

    public function __construct(
        private readonly string $slug,
        private readonly string $name,
        private readonly string $fontFamily
    ) {
    }

    /**
     * @return array{slug: string, name: string, fontFamily: string}
     */
    public function toArray(): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'fontFamily' => $this->fontFamily,
        ];
    }
}
