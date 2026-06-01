<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Styles;

final readonly class BoxSpacing
{
    public function __construct(
        private Spacing $parent,
        private Spacing $box,
    ) {
    }

    public function blockGap(string $value): Spacing
    {
        return $this->parent->blockGap($value);
    }

    public function margin(): self
    {
        return $this->parent->margin();
    }

    public function padding(): self
    {
        return $this->parent->padding();
    }

    public function property(string $property, string $value): self
    {
        $this->box->property($property, $value);

        return $this;
    }

    public function top(string $value): self
    {
        $this->box->top($value);

        return $this;
    }

    public function right(string $value): self
    {
        $this->box->right($value);

        return $this;
    }

    public function bottom(string $value): self
    {
        $this->box->bottom($value);

        return $this;
    }

    public function left(string $value): self
    {
        $this->box->left($value);

        return $this;
    }

    /**
     * @param string[] $values
     */
    public function shorthand(array $values): self
    {
        $this->box->shorthand($values);

        return $this;
    }

    public function vertical(string $value): self
    {
        $this->box->vertical($value);

        return $this;
    }

    public function horizontal(string $value): self
    {
        $this->box->horizontal($value);

        return $this;
    }

    public function verticalAsync(string $top, string $bottom): self
    {
        $this->box->verticalAsync($top, $bottom);

        return $this;
    }

    public function horizontalAsync(string $right, string $left): self
    {
        $this->box->horizontalAsync($right, $left);

        return $this;
    }
}
