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
    private string $color = '';

    public function inset(bool $inset = true): self
    {
        $this->inset = $inset;
        return $this;
    }

    public function offsetX(string $x): self
    {
        $this->assertIsUnique($this->x, 'offset-x');
        $this->assertValidCssDimension($x);
        $this->x = $x;
        return $this;
    }

    public function offsetY(string $y): self
    {
        $this->assertIsUnique($this->y, 'offset-y');
        $this->assertValidCssDimension($y);
        $this->y = $y;
        return $this;
    }

    public function blur(string $blur): self
    {
        $this->assertIsUnique($this->blur, 'blur');
        $this->assertValidCssDimension($blur);
        $this->blur = $blur;
        return $this;
    }

    public function spread(string $spread): self
    {
        $this->assertIsUnique($this->spread, 'spread');
        $this->assertValidCssDimension($spread);
        $this->spread = $spread;
        return $this;
    }

    /**
     * @param Palette|ColorInterface|string $color
     * @throws \Exception
     */
    public function color($color): self
    {
        $this->assertIsUnique($this->color, 'color');

        if ($color instanceof Palette) {
            $this->color = $color->var((string)$color->color());
            return $this;
        }

        if ($color instanceof ColorInterface) {
            $this->color = (string)$color;
            return $this;
        }

        $this->color = (string)(new ColorFactory())->fromColorString($color);
        return $this;
    }

    public function __toString(): string
    {
        if ($this->x === '' && $this->y === '') {
            throw new \RuntimeException('You must add at least 2 value, offset-x and offset-y');
        }

        $shadow = [
            $this->inset ? 'inset' : '',
            $this->x,
            $this->y,
            $this->blur,
            $this->spread,
            $this->color,
        ];

        $this->reset();
        return \trim(\implode(' ', \array_filter($shadow, static fn($value) => $value !== '')));
    }

    public function __clone()
    {
        $this->reset();
    }

    private function assertValidCssDimension(string $value): void
    {
        $unit = \implode('|', [
            'px',
            'em',
            'rem',
            '%',
            'in',
            'cm',
            'mm',
            'pt',
            'pc',
            'ch',
            'ex',
            'vw',
            'vh',
            'vmin',
            'vmax',
        ]);

        if (!\preg_match('/^(-?\d*\.?\d+)(' . $unit . ')$/', $value) && $value !== '0') {
            throw new \RuntimeException('Invalid CSS dimension: given ' . $value);
        }
    }

    private function assertIsUnique(string $param, string $name): void
    {
        if ($param !== '') {
            throw new \RuntimeException('You can add only one value for ' . $name);
        }
    }

    /**
     * @return void
     */
    private function reset(): void
    {
        $this->inset = false;
        $this->x = '';
        $this->y = '';
        $this->blur = '';
        $this->spread = '';
        $this->color = '';
    }
}
