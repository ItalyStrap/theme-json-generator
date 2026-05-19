<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color;

use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\BoxShadow;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetTrait;

class Shadow implements PresetInterface
{
    use PresetTrait;

    /**
     * @var string
     */
    public const TYPE = 'shadow';


    /**
     * @var BoxShadow[]
     */
    private array $shadow;

    public function __construct(
        private readonly string $slug,
        private readonly string $name,
        BoxShadow ...$shadow
    ) {
        $this->shadow = $shadow;
    }

    /**
     * @return array{slug: string, name: string, shadow: string}
     */
    public function toArray(): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'shadow' => \trim(\implode(', ', $this->shadow), ', ')
        ];
    }
}
