<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Typography;

use ItalyStrap\ThemeJsonGenerator\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetTrait;

final readonly class FontFamily implements PresetInterface
{
    use PresetTrait;

    /**
     * @var string
     */
    public const TYPE = 'fontFamily';

    public function __construct(
        private string $slug,
        private string $name,
        private string $fontFamily
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
