<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Typography;

use ItalyStrap\ThemeJsonGenerator\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetTrait;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\Utilities\Fluid;

final readonly class FontSize implements PresetInterface
{
    use PresetTrait;

    public const SECTION = 'fontSizes';

    /**
     * @var string
     */
    public const TYPE = 'fontSize';

    public function __construct(
        private string $slug,
        private string $name,
        private string $size,
        private Fluid|false|null $fluid = null
    ) {
        $this->assertSlugIsWellFormed($slug);
    }

    /**
     * @return array{slug: string, name: string, size: string, fluid?: Fluid|false}
     */
    public function toArray(): array
    {
        return \array_filter([
            'slug' => $this->slug,
            'name' => $this->name,
            'size' => $this->size,
            'fluid' => $this->fluid,
        ], static fn (string|Fluid|bool|null $value): bool => null !== $value);
    }
}
