<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\GradientInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetTrait;

final readonly class Gradient implements PresetInterface
{
    use PresetTrait;

    /**
     * @var string
     */
    public const TYPE = 'gradient';

    public function __construct(
        private string $slug,
        private string $name,
        private GradientInterface $gradient
    ) {
        $this->assertSlugIsWellFormed($slug);
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
