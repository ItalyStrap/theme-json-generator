<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Values;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Color;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Factories\ColorFactory;

final class LinearGradient implements GradientInterface
{
    private string $direction = '';

    /**
     * @var string[]
     */
    private array $colors = [];

    public function direction(string $direction): self
    {
        $this->direction = $direction;
        return $this;
    }

    public function colorStop(
        Color|CssColorInterface|string $color,
        string $stop = ''
    ): self {
        if (\is_string($color) && \trim($color) === '') {
            throw new \InvalidArgumentException('Gradient color must not be empty.');
        }

        $colorVar = '';
        if ($color instanceof Color) {
            $colorVar = $color->var((string)$color->color());
        }

        if ($color instanceof CssColorInterface) {
            $colorVar = (string)$color;
        }

        if (\is_string($color)) {
            $colorVar = (string)(new ColorFactory())->fromColorString($color);
        }

        $result = \trim($colorVar . ' ' . $stop);

        $this->colors[] = $result;
        return $this;
    }

    public function __toString(): string
    {
        if (\count($this->colors) < 2) {
            throw new \RuntimeException('You must add at least 2 colors');
        }

        return \sprintf(
            'linear-gradient(%s, %s)',
            $this->direction === '' ? 'to bottom' : $this->direction,
            \implode(', ', $this->colors)
        );
    }

    public function __clone()
    {
        $this->colors = [];
        $this->direction = '';
    }
}
