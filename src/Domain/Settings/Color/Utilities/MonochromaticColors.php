<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings\Color\Utilities;

/**
 * @psalm-api
 */
class MonochromaticColors implements ColorsGenerator
{
    private ColorModifierInterface $color;

    private array $steps;

    public function __construct(ColorModifierInterface $color, array $steps)
    {
        $this->color = $color;
        $this->steps = $steps;
    }

    public function generate(): array
    {

        \arsort($this->steps);
        $colors = [];
        foreach ($this->steps as $weight) {
            $colors[] = $this->color->tint($weight);
        }

        $colors[] = $this->color->tint();

        \asort($this->steps);
        foreach ($this->steps as $weight) {
            $colors[] = $this->color->shade($weight);
        }

        return $colors;
    }
}
