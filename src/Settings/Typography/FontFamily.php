<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Typography;

use ItalyStrap\ThemeJsonGenerator\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetTrait;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\Utilities\FontFace;

final readonly class FontFamily implements PresetInterface
{
    use PresetTrait;

    public const SECTION = 'fontFamilies';

    /**
     * @var string
     */
    public const TYPE = 'fontFamily';

    /**
     * @var list<FontFace>
     */
    private array $fontFace;

    public function __construct(
        private string $slug,
        private string $name,
        private string $fontFamily,
        FontFace ...$fontFace
    ) {
        $this->assertSlugIsWellFormed($slug);
        $this->fontFace = \array_values($fontFace);
    }

    /**
     * @return array{slug: string, name: string, fontFamily: string, fontFace?: list<array{
     *     fontFamily: string,
     *     fontWeight: string|int,
     *     fontStyle: string,
     *     fontDisplay: string,
     *     src: string|list<string>,
     *     fontStretch?: string,
     *     ascentOverride?: string,
     *     descentOverride?: string,
     *     fontVariant?: string,
     *     fontFeatureSettings?: string,
     *     fontVariationSettings?: string,
     *     lineGapOverride?: string,
     *     sizeAdjust?: string,
     *     unicodeRange?: string
     * }>}
     */
    public function toArray(): array
    {
        return \array_filter([
            'slug' => $this->slug,
            'name' => $this->name,
            'fontFamily' => $this->fontFamily,
            'fontFace' => \array_map(
                static fn (FontFace $fontFace): array => $fontFace->toArray(),
                $this->fontFace
            ),
        ], static fn (string|array $value): bool => $value !== []);
    }
}
