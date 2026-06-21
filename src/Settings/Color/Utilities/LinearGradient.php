<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities;

use ItalyStrap\ThemeJsonGenerator\Settings\Color\Palette;

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
        Palette|ColorInterface|string $color,
        string $stop = ''
    ): self {
        if (\is_string($color) && \trim($color) === '') {
            throw new \InvalidArgumentException('Gradient color must not be empty.');
        }

        $colorVar = '';
        if ($color instanceof Palette) {
            $colorVar = $color->var((string)$color->color());
        }

        if ($color instanceof ColorInterface) {
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
