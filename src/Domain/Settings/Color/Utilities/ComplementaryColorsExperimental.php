<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities;

/**
 * @psalm-api
 */
class ComplementaryColorsExperimental implements ColorsGenerator
{
    private ColorModifierInterface $color;

    public function __construct(ColorModifierInterface $color)
    {
        $this->color = $color;
    }

    public function generate(): array
    {
        return [
            $this->color->color()->toHsla(),
            $this->color->complementary()->toHsla()
        ];
    }
}
