<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities;

use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Palette;

/**
 * @psalm-api
 */
class LinearGradient implements GradientInterface
{
    private string $direction;

    private array $colors;

    public function __construct(string $direction, Palette ...$colors)
    {
        $this->direction = $direction;
        $this->colors = $colors;
    }

    public function __toString(): string
    {
        return \sprintf(
            'linear-gradient(%s, %s)',
            $this->direction === '' ? 'to bottom' : $this->direction,
            \implode(', ', \array_map(static fn (Palette $color): string => $color->var(), $this->colors))
        );
    }
}
