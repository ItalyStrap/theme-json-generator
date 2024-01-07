<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Typography;

use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\CommonTrait;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\ItemInterface;

/**
 * @psalm-api
 */
class FontFamily implements ItemInterface
{
    use CommonTrait;

    /**
     * @var string
     */
    public const CATEGORY = 'fontFamily';

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
