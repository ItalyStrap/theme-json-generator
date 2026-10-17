<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Typography\Utilities;

final readonly class FontFace implements \JsonSerializable
{
    private const FONT_DISPLAY_VALUES = [
        'auto',
        'block',
        'fallback',
        'swap',
        'optional',
    ];

    /**
     * @var string|list<string>
     */
    private array|string $src;

    /**
     * @param string|int $fontWeight
     * @param string|list<string> $src
     */
    public function __construct(
        private string $fontFamily,
        private string|int $fontWeight,
        private string $fontStyle,
        private string $fontStretch,
        array|string $src,
        private string $fontDisplay = 'fallback',
        private string $ascentOverride = '',
        private string $descentOverride = '',
        private string $fontVariant = '',
        private string $fontFeatureSettings = '',
        private string $fontVariationSettings = '',
        private string $lineGapOverride = '',
        private string $sizeAdjust = '',
        private string $unicodeRange = ''
    ) {
        if ($this->fontFamily === '') {
            throw new \InvalidArgumentException('Expected a non-empty font family.');
        }

        $this->assertFontDisplay($this->fontDisplay);
        $this->src = $this->normalizeSrc($src);
    }

    /**
     * @return array{
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
     * }
     */
    public function toArray(): array
    {
        $fontFace = [
            'fontFamily' => $this->fontFamily,
            'fontWeight' => $this->fontWeight,
            'fontStyle' => $this->fontStyle,
            'fontDisplay' => $this->fontDisplay,
            'src' => $this->src,
        ];

        $optional = \array_filter([
            'fontStretch' => $this->fontStretch,
            'ascentOverride' => $this->ascentOverride,
            'descentOverride' => $this->descentOverride,
            'fontVariant' => $this->fontVariant,
            'fontFeatureSettings' => $this->fontFeatureSettings,
            'fontVariationSettings' => $this->fontVariationSettings,
            'lineGapOverride' => $this->lineGapOverride,
            'sizeAdjust' => $this->sizeAdjust,
            'unicodeRange' => $this->unicodeRange,
        ], static fn (string $value): bool => $value !== '');

        return [...$fontFace, ...$optional];
    }

    /**
     * @return array{
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
     * }
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    private function assertFontDisplay(string $fontDisplay): void
    {
        if (!\in_array($fontDisplay, self::FONT_DISPLAY_VALUES, true)) {
            throw new \InvalidArgumentException(\sprintf(
                'Expected font display "auto", "block", "fallback", "swap" or "optional", got "%s".',
                $fontDisplay
            ));
        }
    }

    /**
     * @param array<array-key, mixed>|string $src
     * @return string|list<string>
     */
    private function normalizeSrc(array|string $src): array|string
    {
        if (\is_string($src)) {
            if ($src === '') {
                throw new \InvalidArgumentException('Expected at least one font source.');
            }

            return $src;
        }

        if ($src === []) {
            throw new \InvalidArgumentException('Expected at least one font source.');
        }

        foreach ($src as $source) {
            if (!\is_string($source) || $source === '') {
                throw new \InvalidArgumentException('Expected font sources to be non-empty strings.');
            }
        }

        $sources = \array_values($src);

        /** @var list<string> $sources */
        return $sources;
    }
}
