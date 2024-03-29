<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Typography\Utilities;

/**
 * @psalm-api
 */
class FontFace
{
    private string $fontFamily;

    private string $fontWeight;

    private string $fontStyle;

    private string $fontStretch;

    private array $src;

    public function __construct(
        string $fontFamily,
        string $fontWeight,
        string $fontStyle,
        string $fontStretch,
        array $src
    ) {
        $this->fontFamily = $fontFamily;
        $this->fontWeight = $fontWeight;
        $this->fontStyle = $fontStyle;
        $this->fontStretch = $fontStretch;
        $this->src = $src;
    }

    /**
     * @return array{fontFamily: string, fontWeight: string, fontStyle: string, fontStretch: string, src: mixed[]}
     */
    public function toArray(): array
    {
        return [
            'fontFamily' => $this->fontFamily,
            'fontWeight' => $this->fontWeight,
            'fontStyle' => $this->fontStyle,
            'fontStretch' => $this->fontStretch,
            'src' => $this->src,
        ];
    }
}
