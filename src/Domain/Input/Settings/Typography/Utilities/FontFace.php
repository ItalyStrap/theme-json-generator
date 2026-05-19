<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Typography\Utilities;

readonly class FontFace
{
    /**
     * @param string[] $src
     */
    public function __construct(
        private string $fontFamily,
        private string $fontWeight,
        private string $fontStyle,
        private string $fontStretch,
        private array $src
    ) {
    }

    /**
     * @return array{fontFamily: string, fontWeight: string, fontStyle: string, fontStretch: string, src: string[]}
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
