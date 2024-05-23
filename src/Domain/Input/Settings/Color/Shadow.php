<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color;

use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities\BoxShadow;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetTrait;

/**
 * @psalm-api
 */
class Shadow implements PresetInterface
{
    use PresetTrait;

    /**
     * @var string
     */
    public const TYPE = 'shadow';

    private string $slug;
    private string $name;

    /**
     * @var BoxShadow[]
     */
    private array $shadow;

    public function __construct(string $slug, string $name, BoxShadow ...$shadow)
    {
        $this->slug = $slug;
        $this->name = $name;
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
