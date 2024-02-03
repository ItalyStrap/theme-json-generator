<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color;

use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\GradientInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetTrait;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetInterface;

/**
 * @psalm-api
 */
class Gradient implements PresetInterface
{
    use PresetTrait;

    /**
     * @var string
     */
    public const CATEGORY = 'gradient';

    private string $slug;

    private string $name;

    private GradientInterface $gradient;

    public function __construct(string $slug, string $name, GradientInterface $gradient)
    {
        $this->slug = $slug;
        $this->name = $name;
        $this->gradient = $gradient;
    }

    /**
     * @return array{slug: string, name: string, gradient: string}
     */
    public function toArray(): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'gradient' => (string)$this->gradient,
        ];
    }
}
