<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities;

readonly class ComplementaryColorsExperimental implements ColorsGenerator
{
    public function __construct(private ColorModifierInterface $color)
    {
    }

    public function generate(): array
    {
        return [
            $this->color->color()->toHsla(),
            $this->color->complementary()->toHsla()
        ];
    }
}
