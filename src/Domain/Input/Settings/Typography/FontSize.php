<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Typography;

use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\CommonTrait;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\ItemInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Typography\Utilities\Fluid;

/**
 * @psalm-api
 */
class FontSize implements ItemInterface
{
    use CommonTrait;

    /**
     * @var string
     */
    public const CATEGORY = 'fontSize';

    private string $slug;

    private string $name;

    private string $size;

    private ?Fluid $fluid;

    public function __construct(string $slug, string $name, string $size, ?Fluid $fluid = null)
    {
        $this->slug = $slug;
        $this->name = $name;
        $this->size = $size;
        $this->fluid = $fluid;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return \array_filter([
            'slug' => $this->slug,
            'name' => $this->name,
            'size' => $this->size,
            'fluid' => $this->fluid,
        ], static fn ($value): bool => null !== $value);
    }
}
