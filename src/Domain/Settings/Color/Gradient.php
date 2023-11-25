<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color;

use ItalyStrap\ThemeJsonGenerator\Domain\Settings\ItemInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities\GradientInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Settings\CommonTrait;

/**
 * @psalm-api
 */
class Gradient implements ItemInterface
{
    use CommonTrait;

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

    public function toArray(): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'gradient' => (string)$this->gradient,
        ];
    }
}
