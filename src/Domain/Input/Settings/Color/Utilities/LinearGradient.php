<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities;

use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Palette;

/**
 * @psalm-api
 */
class LinearGradient implements GradientInterface
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

    public function colorStop(Palette $color = null, string $stop = ''): self
    {
        $colorVar = '';
        if ($color) {
            $colorVar = $color->var((string)$color->color());
        }

        $result = \trim($colorVar . ' ' . $stop);

        if ($result === '') {
            return $this;
        }

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
