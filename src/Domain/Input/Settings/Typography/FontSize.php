<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Typography;

use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetTrait;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Typography\Utilities\Fluid;

final readonly class FontSize implements PresetInterface
{
    use PresetTrait;

    /**
     * @var string
     */
    public const TYPE = 'fontSize';

    public function __construct(
        private string $slug,
        private string $name,
        private string $size,
        private ?Fluid $fluid = null
    ) {
    }

    /**
     * @return array{slug: string, name: string, size: string, fluid?: Fluid}
     */
    public function toArray(): array
    {
        return \array_filter([
            'slug' => $this->slug,
            'name' => $this->name,
            'size' => $this->size,
            'fluid' => $this->fluid,
        ], static fn (string|Fluid|null $value): bool => null !== $value);
    }
}
