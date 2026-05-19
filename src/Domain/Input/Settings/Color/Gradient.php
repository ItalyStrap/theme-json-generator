<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color;

use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\GradientInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetTrait;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetInterface;

class Gradient implements PresetInterface
{
    use PresetTrait;

    /**
     * @var string
     */
    public const TYPE = 'gradient';

    public function __construct(
        private readonly string $slug,
        private readonly string $name,
        private readonly GradientInterface $gradient
    ) {
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
