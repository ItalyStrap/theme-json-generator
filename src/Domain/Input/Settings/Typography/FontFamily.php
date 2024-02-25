<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Typography;

use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetTrait;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetInterface;

/**
 * @psalm-api
 */
class FontFamily implements PresetInterface
{
    use PresetTrait;

    /**
     * @var string
     */
    public const TYPE = 'fontFamily';

    private string $slug;

    private string $name;

    private string $fontFamily;

    public function __construct(string $slug, string $name, string $fontFamily)
    {
        $this->slug = $slug;
        $this->name = $name;
        $this->fontFamily = $fontFamily;
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
