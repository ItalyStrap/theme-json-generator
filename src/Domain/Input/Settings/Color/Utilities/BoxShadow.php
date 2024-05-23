<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Utilities;

use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Color\Palette;

/**
 * @psalm-api
 */
class BoxShadow
{
    private bool $inset = false;
    private string $x = '';
    private string $y = '';
    private string $blur = '';
    private string $spread = '';
    private ?string $color = '';

    public function inset(bool $inset = true): self
    {
        $this->inset = $inset;
        return $this;
    }

    public function offsetX(string $x): self
    {
        $this->assertValidCssDimension($x);
        $this->x = $x;
        return $this;
    }

    public function offsetY(string $y): self
    {
        $this->assertValidCssDimension($y);
        $this->y = $y;
        return $this;
    }

    public function blur(string $blur): self
    {
        $this->assertValidCssDimension($blur);
        $this->blur = $blur;
        return $this;
    }

    public function spread(string $spread): self
    {
        $this->assertValidCssDimension($spread);
        $this->spread = $spread;
        return $this;
    }

    public function color(Palette $color): self
    {
        $this->color = $color->var((string)$color->color());
        return $this;
    }

    public function __toString(): string
    {
        if ($this->x === '' && $this->y === '') {
            throw new \RuntimeException('You must add at least 2 value');
        }

        $shadow = [
            $this->inset ? 'inset ' : '',
            $this->x,
            $this->y,
            $this->blur,
            $this->spread,
            $this->color,
        ];

        return \trim(\implode(' ', \array_filter($shadow)));
    }

    public function __clone()
    {
        $this->inset = false;
        $this->x = '';
        $this->y = '';
        $this->blur = '';
        $this->spread = '';
        $this->color = '';
    }

    private function assertValidCssDimension(string $value): void
    {
        if (!\preg_match('/^(-?\d*\.?\d+)(px|em|rem|%)$/', $value)) {
            throw new \RuntimeException('Invalid CSS dimension');
        }
    }
}
